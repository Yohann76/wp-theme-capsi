#!/usr/bin/env bash
set -euo pipefail

# ============================================================
# L'Atelier Capsi — WordPress configuration script
# Usage: called by `make dev-configure`
# ============================================================

WP_URL="http://51.178.80.14:8080"
WP_TITLE="L'Atelier Capsi"
WP_ADMIN="admin"
WP_PASS="capsi2024"
WP_EMAIL="yohanndurand76@gmail.com"

WP="docker compose run --rm wpcli"

# ============================================================
# Helpers
# ============================================================

step() { echo ""; echo ">>> $1"; }

page_id() {
  $WP post list --post_type=page --post_status=publish --title="$1" --field=ID --format=ids
}

cat_id() {
  $WP term list category --name="$1" --field=term_id --format=ids
}

wc_cat_id() {
  $WP wc product_cat list --format=json --user="$WP_ADMIN" \
    | python3 -c "import sys,json;cats=json.load(sys.stdin);print([c['id'] for c in cats if c['name']=='$1'][0])"
}

# ============================================================
# Wait for services
# ============================================================

step "Waiting for database..."
until docker compose exec db mysql -ucapsi_user -pcapsi_pass capsi_db -e "SELECT 1" >/dev/null 2>&1; do
  sleep 3
done

step "Waiting for WordPress..."
until docker compose exec wordpress curl -s http://localhost >/dev/null 2>&1; do
  sleep 3
done

# ============================================================
# WordPress core
# ============================================================

step "Installing WordPress..."
$WP core install \
  --url="$WP_URL" \
  --title="$WP_TITLE" \
  --admin_user="$WP_ADMIN" \
  --admin_password="$WP_PASS" \
  --admin_email="$WP_EMAIL" \
  --skip-email

# ============================================================
# Language & regional settings
# ============================================================

step "Setting language to French..."
$WP language core install fr_FR
$WP site switch-language fr_FR
$WP option update timezone_string "Europe/Paris"
$WP option update date_format "d/m/Y"
$WP option update time_format "H:i"
$WP option update start_of_week 1

# ============================================================
# Theme
# ============================================================

step "Activating theme..."
$WP theme activate capsi-theme
$WP option update blogdescription ""

# ============================================================
# Cleanup default content
# ============================================================

step "Removing default content..."
$WP post delete 1 2 --force 2>/dev/null || true

# ============================================================
# Pages
# ============================================================

step "Creating pages..."
$WP post create --post_type=page --post_status=publish --post_title="Accueil"
$WP post create --post_type=page --post_status=publish --post_title="Variétés de piments"
$WP post create --post_type=page --post_status=publish --post_title="Blog"
$WP post create --post_type=page --post_status=publish --post_title="À propos"
$WP post create --post_type=page --post_status=publish --post_title="Contact"
$WP post create --post_type=page --post_status=publish --post_title="Livraison"
$WP post create --post_type=page --post_status=publish --post_title="FAQ"
$WP post create --post_type=page --post_status=publish --post_title="Mentions légales"
$WP post create --post_type=page --post_status=publish --post_title="Conditions Générales de Vente"
$WP post create --post_type=page --post_status=publish --post_title="Politique de confidentialité"

step "Configuring front page & blog..."
$WP option update show_on_front page
$WP option update page_on_front "$(page_id "Accueil")"
$WP option update page_for_posts "$(page_id "Blog")"

# ============================================================
# Navigation menu
# ============================================================

step "Creating navigation menu..."
$WP menu create "Menu principal"
$WP menu item add-post "Menu principal" "$(page_id "Accueil")"             --title="Accueil"
$WP menu item add-post "Menu principal" "$(page_id "Variétés de piments")" --title="Variétés"
$WP menu item add-post "Menu principal" "$(page_id "Blog")"                --title="Blog"
$WP menu item add-post "Menu principal" "$(page_id "À propos")"            --title="À propos"
$WP menu item add-post "Menu principal" "$(page_id "Contact")"             --title="Contact"
$WP menu location assign "Menu principal" primary

step "Creating footer menus..."

$WP menu create "Footer Produits"
$WP menu item add-custom "Footer Produits" "Graines"  "$WP_URL/boutique/graines"
$WP menu item add-custom "Footer Produits" "Plants"   "$WP_URL/boutique/plants"
$WP menu item add-custom "Footer Produits" "Sauces"   "$WP_URL/boutique/sauces-condiments"
$WP menu item add-custom "Footer Produits" "Coffrets" "$WP_URL/boutique/coffrets"
$WP menu location assign "Footer Produits" footer-produits

$WP menu create "Footer Informations"
$WP menu item add-post "Footer Informations" "$(page_id "À propos")"  --title="À propos"
$WP menu item add-post "Footer Informations" "$(page_id "Contact")"   --title="Contact"
$WP menu item add-post "Footer Informations" "$(page_id "Livraison")" --title="Livraison"
$WP menu item add-post "Footer Informations" "$(page_id "FAQ")"       --title="FAQ"
$WP menu location assign "Footer Informations" footer-infos

$WP menu create "Footer Légal"
$WP menu item add-post "Footer Légal" "$(page_id "Mentions légales")"                --title="Mentions légales"
$WP menu item add-post "Footer Légal" "$(page_id "Conditions Générales de Vente")"   --title="CGV"
$WP menu item add-post "Footer Légal" "$(page_id "Politique de confidentialité")"    --title="Confidentialité"
$WP menu location assign "Footer Légal" footer-legal

# ============================================================
# Blog categories
# ============================================================

step "Creating blog categories..."
$WP term create category "Actualités"       --description="Les dernières nouvelles de L'Atelier Capsi"
$WP term create category "Culture & Conseils" --description="Guides de culture, conseils et techniques"
$WP term create category "Variétés"         --description="Présentation des variétés rares et de collection"
$WP term create category "Recettes"         --description="Recettes et utilisations culinaires"
$WP term delete category 1

# ============================================================
# Blog posts (demo)
# ============================================================

step "Creating demo blog posts..."
$WP post create \
  --post_type=post --post_status=publish \
  --post_title="Bienvenue à L'Atelier Capsi" \
  --post_content="L'Atelier Capsi est le conservatoire des piments rares et de collection. Découvrez notre univers botanique, nos variétés d'exception et notre passion pour le capsicum." \
  --post_category="$(cat_id "Actualités")"

$WP post create \
  --post_type=post --post_status=publish \
  --post_title="Comment cultiver le Habanero en pot" \
  --post_content="Le Habanero est l'une des variétés les plus populaires de notre collection. Voici nos conseils pour le cultiver avec succès en pot, de la germination à la récolte." \
  --post_category="$(cat_id "Culture & Conseils")"

$WP post create \
  --post_type=post --post_status=publish \
  --post_title="Focus : le Carolina Reaper, roi des piments" \
  --post_content="Avec ses 2,2 millions de Scoville, le Carolina Reaper détient le record mondial. Découvrez l'histoire de cette variété extraordinaire et comment elle est cultivée à l'Atelier." \
  --post_category="$(cat_id "Variétés")"

$WP post create \
  --post_type=post --post_status=publish \
  --post_title="Sauce piquante artisanale au Ghost Pepper" \
  --post_content="Le Ghost Pepper, ou Bhut Jolokia, donne une sauce d'une profondeur aromatique incomparable. Voici notre recette maison pour une sauce artisanale intense et fruitée." \
  --post_category="$(cat_id "Recettes")"

# ============================================================
# Permalinks
# ============================================================

step "Configuring permalinks..."
$WP option update permalink_structure "/%postname%/"
$WP rewrite flush

# ============================================================
# WooCommerce
# ============================================================

step "Installing WooCommerce..."
$WP plugin install woocommerce --activate

step "Configuring WooCommerce settings..."
$WP option update woocommerce_store_address   "1 rue de l'Atelier"
$WP option update woocommerce_store_city      "Paris"
$WP option update woocommerce_default_country "FR"
$WP option update woocommerce_currency        "EUR"
$WP option update woocommerce_currency_pos    "right_space"
$WP option update woocommerce_price_thousand_sep " "
$WP option update woocommerce_price_decimal_sep  ","
$WP option update woocommerce_price_num_decimals "2"
$WP option update woocommerce_calc_taxes         "yes"
$WP option update woocommerce_tax_display_shop   "incl"
$WP option update woocommerce_tax_display_cart   "incl"
$WP option update woocommerce_enable_reviews     "yes"
$WP option update woocommerce_catalog_columns    "3"
$WP option update woocommerce_catalog_rows       "4"

step "Creating WooCommerce pages..."
$WP wc tool run install_pages --user="$WP_ADMIN"

step "Creating product categories..."
$WP wc product_cat create --name="Graines"             --description="Graines de piments rares et de collection" --user="$WP_ADMIN"
$WP wc product_cat create --name="Plants"              --description="Plants de piments prêts à planter"         --user="$WP_ADMIN"
$WP wc product_cat create --name="Sauces et Condiments" --description="Sauces artisanales et condiments"          --user="$WP_ADMIN"
$WP wc product_cat create --name="Coffrets"            --description="Coffrets cadeaux et collections"           --user="$WP_ADMIN"

step "Creating demo products..."
$WP wc product create \
  --name="Graines Carolina Reaper — 20 graines" \
  --type=simple --regular_price="6.90" --status=publish \
  --description="Le Carolina Reaper est le piment le plus fort du monde avec 2,2 millions de Scoville. Graines issues de notre sélection maison, taux de germination > 90%." \
  --short_description="2,2M Scoville · Lot de 20 graines · Sélection maison" \
  --categories='[{"id":'"$(wc_cat_id "Graines")"'}]' \
  --manage_stock=true --stock_quantity=50 --user="$WP_ADMIN"

$WP wc product create \
  --name="Graines Habanero Orange — 20 graines" \
  --type=simple --regular_price="4.90" --status=publish \
  --description="L'Habanero Orange est un classique fruité et intense. Notes tropicales, 350 000 Scoville. Parfait pour débutants et confirmés." \
  --short_description="350K Scoville · Lot de 20 graines · Fruité & intense" \
  --categories='[{"id":'"$(wc_cat_id "Graines")"'}]' \
  --manage_stock=true --stock_quantity=80 --user="$WP_ADMIN"

$WP wc product create \
  --name="Plant Bhut Jolokia (Ghost Pepper)" \
  --type=simple --regular_price="8.50" --status=publish \
  --description="Plant de Ghost Pepper élevé en serre, prêt à planter. Variété légendaire originaire d'Inde. 1 000 000 Scoville. Livraison en pot biodégradable." \
  --short_description="1M Scoville · Plant prêt à planter · Pot biodégradable" \
  --categories='[{"id":'"$(wc_cat_id "Plants")"'}]' \
  --manage_stock=true --stock_quantity=15 --user="$WP_ADMIN"

$WP wc product create \
  --name="Sauce artisanale Habanero & Mangue" \
  --type=simple --regular_price="9.90" --status=publish \
  --description="Sauce artisanale élaborée à partir de nos piments Habanero cultivés à l'Atelier, associés à la mangue. Flacon verre ambré 100ml." \
  --short_description="Flacon 100ml · Artisanal · Habanero & Mangue" \
  --categories='[{"id":'"$(wc_cat_id "Sauces et Condiments")"'}]' \
  --manage_stock=true --stock_quantity=30 --user="$WP_ADMIN"

$WP wc product create \
  --name="Coffret Découverte — 5 variétés rares" \
  --type=simple --regular_price="24.90" --sale_price="19.90" --status=publish \
  --description="Coffret de 5 sachets de graines rares : Carolina Reaper, Ghost Pepper, Habanero Chocolate, Moruga Scorpion et Aji Amarillo. Idéal en cadeau." \
  --short_description="5 variétés rares · Édition limitée · Idéal cadeau" \
  --categories='[{"id":'"$(wc_cat_id "Coffrets")"'}]' \
  --manage_stock=true --stock_quantity=20 --user="$WP_ADMIN"

# ============================================================
# Done
# ============================================================

echo ""
echo "✓ WordPress configured successfully!"
echo "  Site    : $WP_URL"
echo "  Shop    : $WP_URL/boutique"
echo "  Admin   : $WP_URL/wp-admin"
echo "  Login   : $WP_ADMIN / $WP_PASS"
