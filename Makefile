.PHONY: dev-build dev-install dev-run dev-kill dev-configure dev-reload

WP_URL    = http://51.178.80.14:8080
WP_TITLE  = L'Atelier Capsi
WP_ADMIN  = admin
WP_PASS   = capsi2024
WP_EMAIL  = yohanndurand76@gmail.com
WP        = docker compose run --rm wpcli

dev-build:
	docker compose pull
	$(MAKE) dev-install

dev-install:
	docker run --rm -v $(PWD)/theme:/app composer:2 install --working-dir=/app --no-interaction --optimize-autoloader

dev-reload:
	$(MAKE) dev-kill
	docker volume rm wp-theme-capsi_wp_data wp-theme-capsi_db_data 2>/dev/null || true
	$(MAKE) dev-build
	$(MAKE) dev-run
	$(MAKE) dev-configure

dev-run:
	docker compose up -d

dev-kill:
	docker compose down

dev-configure:
	@echo ">>> Attente base de données..."
	@until docker compose exec db mysql -ucapsi_user -pcapsi_pass capsi_db -e "SELECT 1" >/dev/null 2>&1; do sleep 3; done
	@echo ">>> Attente WordPress..."
	@until docker compose exec wordpress curl -s http://localhost >/dev/null 2>&1; do sleep 3; done

	@echo ">>> Installation WordPress..."
	$(WP) core install \
		--url="$(WP_URL)" \
		--title="$(WP_TITLE)" \
		--admin_user="$(WP_ADMIN)" \
		--admin_password="$(WP_PASS)" \
		--admin_email="$(WP_EMAIL)" \
		--skip-email

	@echo ">>> Langue française..."
	$(WP) language core install fr_FR
	$(WP) site switch-language fr_FR
	$(WP) option update timezone_string "Europe/Paris"
	$(WP) option update date_format "d/m/Y"
	$(WP) option update time_format "H:i"
	$(WP) option update start_of_week 1

	@echo ">>> Thème..."
	$(WP) theme activate capsi-theme
	$(WP) option update blogdescription ""

	@echo ">>> Suppression contenu par défaut..."
	$(WP) post delete 1 2 --force

	@echo ">>> Pages..."
	$(WP) post create --post_type=page --post_status=publish --post_title="Accueil"
	$(WP) post create --post_type=page --post_status=publish --post_title="Variétés de piments"
	$(WP) post create --post_type=page --post_status=publish --post_title="Blog"
	$(WP) post create --post_type=page --post_status=publish --post_title="À propos"
	$(WP) post create --post_type=page --post_status=publish --post_title="Contact"

	@echo ">>> Configuration page d'accueil et blog..."
	$(WP) option update show_on_front page
	$(WP) option update page_on_front $$($(WP) post list --post_type=page --post_status=publish --title="Accueil" --field=ID --format=ids)
	$(WP) option update page_for_posts $$($(WP) post list --post_type=page --post_status=publish --title="Blog" --field=ID --format=ids)

	@echo ">>> Menu de navigation..."
	$(WP) menu create "Menu principal"
	$(WP) menu item add-post "Menu principal" $$($(WP) post list --post_type=page --post_status=publish --title="Accueil" --field=ID --format=ids) --title="Accueil"
	$(WP) menu item add-post "Menu principal" $$($(WP) post list --post_type=page --post_status=publish --title="Variétés de piments" --field=ID --format=ids) --title="Variétés"
	$(WP) menu item add-post "Menu principal" $$($(WP) post list --post_type=page --post_status=publish --title="Blog" --field=ID --format=ids) --title="Blog"
	$(WP) menu item add-post "Menu principal" $$($(WP) post list --post_type=page --post_status=publish --title="À propos" --field=ID --format=ids) --title="À propos"
	$(WP) menu item add-post "Menu principal" $$($(WP) post list --post_type=page --post_status=publish --title="Contact" --field=ID --format=ids) --title="Contact"
	$(WP) menu location assign "Menu principal" primary

	@echo ">>> Catégories blog..."
	$(WP) term create category "Actualités" --description="Les dernières nouvelles de L'Atelier Capsi"
	$(WP) term create category "Culture & Conseils" --description="Guides de culture, conseils et techniques"
	$(WP) term create category "Variétés" --description="Présentation des variétés rares et de collection"
	$(WP) term create category "Recettes" --description="Recettes et utilisations culinaires"
	$(WP) term delete category 1

	@echo ">>> Articles de blog..."
	$(WP) post create \
		--post_type=post --post_status=publish \
		--post_title="Bienvenue à L'Atelier Capsi" \
		--post_content="L'Atelier Capsi est le conservatoire des piments rares et de collection. Découvrez notre univers botanique, nos variétés d'exception et notre passion pour le capsicum." \
		--post_category=$$($(WP) term list category --name="Actualités" --field=term_id --format=ids)
	$(WP) post create \
		--post_type=post --post_status=publish \
		--post_title="Comment cultiver le Habanero en pot" \
		--post_content="Le Habanero est l'une des variétés les plus populaires de notre collection. Voici nos conseils pour le cultiver avec succès en pot, de la germination à la récolte." \
		--post_category=$$($(WP) term list category --name="Culture & Conseils" --field=term_id --format=ids)
	$(WP) post create \
		--post_type=post --post_status=publish \
		--post_title="Focus : le Carolina Reaper, roi des piments" \
		--post_content="Avec ses 2,2 millions de Scoville, le Carolina Reaper détient le record mondial. Découvrez l'histoire de cette variété extraordinaire et comment elle est cultivée à l'Atelier." \
		--post_category=$$($(WP) term list category --name="Variétés" --field=term_id --format=ids)
	$(WP) post create \
		--post_type=post --post_status=publish \
		--post_title="Sauce piquante artisanale au Ghost Pepper" \
		--post_content="Le Ghost Pepper, ou Bhut Jolokia, donne une sauce d'une profondeur aromatique incomparable. Voici notre recette maison pour une sauce artisanale intense et fruitée." \
		--post_category=$$($(WP) term list category --name="Recettes" --field=term_id --format=ids)

	@echo ">>> Permaliens..."
	$(WP) option update permalink_structure "/%postname%/"
	$(WP) rewrite flush

	@echo ">>> WooCommerce — installation..."
	$(WP) plugin install woocommerce --activate

	@echo ">>> WooCommerce — paramètres boutique..."
	$(WP) option update woocommerce_store_address "1 rue de l'Atelier"
	$(WP) option update woocommerce_store_city "Paris"
	$(WP) option update woocommerce_default_country "FR"
	$(WP) option update woocommerce_currency "EUR"
	$(WP) option update woocommerce_currency_pos "right_space"
	$(WP) option update woocommerce_price_thousand_sep " "
	$(WP) option update woocommerce_price_decimal_sep ","
	$(WP) option update woocommerce_price_num_decimals "2"
	$(WP) option update woocommerce_calc_taxes "yes"
	$(WP) option update woocommerce_tax_display_shop "incl"
	$(WP) option update woocommerce_tax_display_cart "incl"
	$(WP) option update woocommerce_enable_reviews "yes"
	$(WP) option update woocommerce_catalog_columns "3"
	$(WP) option update woocommerce_catalog_rows "4"

	@echo ">>> WooCommerce — pages boutique..."
	$(WP) wc tool run install_pages --user=$(WP_ADMIN)

	@echo ">>> WooCommerce — catégories produits..."
	$(WP) wc product_cat create --name="Graines" --description="Graines de piments rares et de collection" --user=$(WP_ADMIN)
	$(WP) wc product_cat create --name="Plants" --description="Plants de piments prêts à planter" --user=$(WP_ADMIN)
	$(WP) wc product_cat create --name="Sauces & Condiments" --description="Sauces artisanales et condiments au piment" --user=$(WP_ADMIN)
	$(WP) wc product_cat create --name="Coffrets" --description="Coffrets cadeaux et collections" --user=$(WP_ADMIN)

	@echo ">>> WooCommerce — produits démo..."
	$(WP) wc product create \
		--name="Graines Carolina Reaper — 20 graines" \
		--type=simple \
		--regular_price="6.90" \
		--status=publish \
		--description="Le Carolina Reaper est le piment le plus fort du monde avec 2,2 millions de Scoville. Graines issues de notre sélection maison, taux de germination > 90%." \
		--short_description="2,2M Scoville · Lot de 20 graines · Sélection maison" \
		--categories='[{"id":'$$($(WP) wc product_cat list --format=json --user=$(WP_ADMIN) | python3 -c "import sys,json;cats=json.load(sys.stdin);print([c['id'] for c in cats if c['name']=='Graines'][0])")'"}]' \
		--manage_stock=true \
		--stock_quantity=50 \
		--user=$(WP_ADMIN)
	$(WP) wc product create \
		--name="Graines Habanero Orange — 20 graines" \
		--type=simple \
		--regular_price="4.90" \
		--status=publish \
		--description="L'Habanero Orange est un classique fruité et intense. Notes tropicales, 350 000 Scoville. Parfait pour débutants et confirmés." \
		--short_description="350K Scoville · Lot de 20 graines · Fruité & intense" \
		--categories='[{"id":'$$($(WP) wc product_cat list --format=json --user=$(WP_ADMIN) | python3 -c "import sys,json;cats=json.load(sys.stdin);print([c['id'] for c in cats if c['name']=='Graines'][0])")'"}]' \
		--manage_stock=true \
		--stock_quantity=80 \
		--user=$(WP_ADMIN)
	$(WP) wc product create \
		--name="Plant Bhut Jolokia (Ghost Pepper)" \
		--type=simple \
		--regular_price="8.50" \
		--status=publish \
		--description="Plant de Ghost Pepper élevé en serre, prêt à planter. Variété légendaire originaire d'Inde. 1 000 000 Scoville. Livraison en pot biodégradable." \
		--short_description="1M Scoville · Plant prêt à planter · Pot biodégradable" \
		--categories='[{"id":'$$($(WP) wc product_cat list --format=json --user=$(WP_ADMIN) | python3 -c "import sys,json;cats=json.load(sys.stdin);print([c['id'] for c in cats if c['name']=='Plants'][0])")'"}]' \
		--manage_stock=true \
		--stock_quantity=15 \
		--user=$(WP_ADMIN)
	$(WP) wc product create \
		--name="Sauce artisanale Habanero & Mangue" \
		--type=simple \
		--regular_price="9.90" \
		--status=publish \
		--description="Sauce artisanale élaborée à partir de nos piments Habanero cultivés à l'Atelier, associés à la mangue pour une explosion fruitée et intense. Flacon verre ambré 100ml." \
		--short_description="Flacon 100ml · Artisanal · Habanero & Mangue" \
		--categories='[{"id":'$$($(WP) wc product_cat list --format=json --user=$(WP_ADMIN) | python3 -c "import sys,json;cats=json.load(sys.stdin);print([c['id'] for c in cats if c['name']=='Sauces & Condiments'][0])")'"}]' \
		--manage_stock=true \
		--stock_quantity=30 \
		--user=$(WP_ADMIN)
	$(WP) wc product create \
		--name="Coffret Découverte — 5 variétés rares" \
		--type=simple \
		--regular_price="24.90" \
		--sale_price="19.90" \
		--status=publish \
		--description="Coffret de 5 sachets de graines de variétés rares sélectionnées par L'Atelier Capsi : Carolina Reaper, Ghost Pepper, Habanero Chocolate, Piment Moruga Scorpion et Aji Amarillo. Idéal en cadeau." \
		--short_description="5 variétés rares · Édition limitée · Idéal cadeau" \
		--categories='[{"id":'$$($(WP) wc product_cat list --format=json --user=$(WP_ADMIN) | python3 -c "import sys,json;cats=json.load(sys.stdin);print([c['id'] for c in cats if c['name']=='Coffrets'][0])")'"}]' \
		--manage_stock=true \
		--stock_quantity=20 \
		--user=$(WP_ADMIN)

	@echo ""
	@echo "✓ WordPress + WooCommerce configurés !"
	@echo "  URL       : $(WP_URL)"
	@echo "  Boutique  : $(WP_URL)/boutique"
	@echo "  Admin     : $(WP_URL)/wp-admin"
	@echo "  Login     : $(WP_ADMIN) / $(WP_PASS)"
