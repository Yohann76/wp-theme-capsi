.PHONY: dev-build dev-run dev-kill dev-configure

WP_URL    = http://51.178.80.14:8080
WP_TITLE  = L'Atelier Capsi
WP_ADMIN  = admin
WP_PASS   = capsi2024
WP_EMAIL  = yohanndurand76@gmail.com
WP        = docker compose run --rm wpcli

dev-build:
	docker compose pull

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
	$(WP) post create --post_type=page --post_status=publish --post_title="À propos"
	$(WP) post create --post_type=page --post_status=publish --post_title="Services"
	$(WP) post create --post_type=page --post_status=publish --post_title="Contact"
	$(WP) post create --post_type=page --post_status=publish --post_title="Blog"

	@echo ">>> Configuration page d'accueil et blog..."
	$(WP) option update show_on_front page
	$(WP) option update page_on_front $$($(WP) post list --post_type=page --post_status=publish --title="Accueil" --field=ID --format=ids)
	$(WP) option update page_for_posts $$($(WP) post list --post_type=page --post_status=publish --title="Blog" --field=ID --format=ids)

	@echo ">>> Catégories..."
	$(WP) term create category "Actualités" --description="Les dernières actualités de L'Atelier Capsi"
	$(WP) term create category "Conseils" --description="Conseils et bonnes pratiques"
	$(WP) term create category "Projets" --description="Nos réalisations et projets clients"
	$(WP) term delete category 1

	@echo ">>> Articles de blog..."
	$(WP) post create \
		--post_type=post --post_status=publish \
		--post_title="Bienvenue sur le blog de L'Atelier Capsi" \
		--post_content="Découvrez notre univers, nos projets et notre vision. L'Atelier Capsi vous accompagne dans vos projets créatifs avec passion et expertise." \
		--post_category=$$($(WP) term list category --name="Actualités" --field=term_id --format=ids)
	$(WP) post create \
		--post_type=post --post_status=publish \
		--post_title="5 conseils pour bien démarrer votre projet" \
		--post_content="Un bon projet commence toujours par une bonne préparation. Voici 5 conseils essentiels pour mettre toutes les chances de votre côté dès le départ." \
		--post_category=$$($(WP) term list category --name="Conseils" --field=term_id --format=ids)
	$(WP) post create \
		--post_type=post --post_status=publish \
		--post_title="Notre première réalisation de l'année" \
		--post_content="Nous sommes fiers de vous présenter notre dernière réalisation. Ce projet a été une belle aventure humaine et créative, du brief initial jusqu'à la livraison finale." \
		--post_category=$$($(WP) term list category --name="Projets" --field=term_id --format=ids)

	@echo ">>> Permaliens..."
	$(WP) option update permalink_structure "/%postname%/"
	$(WP) rewrite flush

	@echo ""
	@echo "✓ WordPress configuré en français !"
	@echo "  URL     : $(WP_URL)"
	@echo "  Admin   : $(WP_URL)/wp-admin"
	@echo "  Login   : $(WP_ADMIN) / $(WP_PASS)"
