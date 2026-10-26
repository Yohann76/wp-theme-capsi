#!/usr/bin/env bash
# WooCommerce — installation, configuration, catégories, produits

step "Installing WooCommerce..."
$WP plugin install woocommerce --activate
$WP language plugin install woocommerce fr_FR

step "Configuring WooCommerce settings..."
$WP option update woocommerce_coming_soon          "no"
$WP option update woocommerce_store_address        "1 rue de l'Atelier"
$WP option update woocommerce_store_city           "Paris"
$WP option update woocommerce_default_country      "FR"
$WP option update woocommerce_currency             "EUR"
$WP option update woocommerce_currency_pos         "right_space"
$WP option update woocommerce_price_thousand_sep   " "
$WP option update woocommerce_price_decimal_sep    ","
$WP option update woocommerce_price_num_decimals   "2"
$WP option update woocommerce_calc_taxes           "yes"
$WP option update woocommerce_tax_display_shop     "incl"
$WP option update woocommerce_tax_display_cart     "incl"
$WP option update woocommerce_enable_reviews                   "yes"
$WP option update woocommerce_enable_myaccount_registration    "yes"
$WP option update woocommerce_registration_generate_username   "yes"
$WP option update woocommerce_registration_generate_password   "no"
$WP option update woocommerce_catalog_columns      "3"
$WP option update woocommerce_catalog_rows         "4"

step "Creating WooCommerce pages..."
$WP wc tool run install_pages --user="$WP_ADMIN"
# Forcer WooCommerce à utiliser la page Boutique déjà créée (évite les slugs "shop" en anglais)
$WP option update woocommerce_shop_page_id "$(page_id "Boutique")"
$WP rewrite flush

step "Creating product categories..."
$WP wc product_cat create \
  --name="Graines" \
  --description="Graines de piments rares et de collection" \
  --user="$WP_ADMIN"

$WP wc product_cat create \
  --name="Plants" \
  --description="Plants de piments et plantes aromatiques rares, prêts à planter" \
  --user="$WP_ADMIN"

step "Creating demo products..."
$WP wc product create \
  --name="Graines Carolina Reaper — 20 graines" \
  --type=simple --regular_price="6.90" --status=publish \
  --description="Le Carolina Reaper est le piment le plus fort du monde avec 2,2 millions de Scoville. Graines issues de notre sélection maison, taux de germination <strong>supérieur à 90%</strong>. Idéal pour les amateurs de sensations extrêmes et les collectionneurs." \
  --short_description="<strong>2,2M Scoville</strong> · Lot de 20 graines · Sélection maison" \
  --categories='[{"id":'"$(wc_cat_id "Graines")"'}]' \
  --manage_stock=true --stock_quantity=50 --user="$WP_ADMIN"

$WP wc product create \
  --name="Graines Habanero Orange — 20 graines" \
  --type=simple --regular_price="4.90" --status=publish \
  --description="L'Habanero Orange est un classique fruité et intense. Notes tropicales, <strong>350 000 Scoville</strong>. Parfait pour débuter la collection ou offrir à un amateur de variétés rares." \
  --short_description="<strong>350K Scoville</strong> · Lot de 20 graines · Fruité & intense" \
  --categories='[{"id":'"$(wc_cat_id "Graines")"'}]' \
  --manage_stock=true --stock_quantity=80 --user="$WP_ADMIN"

$WP wc product create \
  --name="Plant Bhut Jolokia (Ghost Pepper)" \
  --type=simple --regular_price="8.50" --status=publish \
  --description="Plant de Ghost Pepper élevé en serre, <strong>prêt à planter</strong>. Variété légendaire originaire d'Inde. 1 000 000 Scoville. Livraison en pot biodégradable avec fiche culturale." \
  --short_description="<strong>1M Scoville</strong> · Plant prêt à planter · Pot biodégradable" \
  --categories='[{"id":'"$(wc_cat_id "Plants")"'}]' \
  --manage_stock=true --stock_quantity=15 --user="$WP_ADMIN"

$WP wc product create \
  --name="Plant Thym Citron — variété rare" \
  --type=simple --regular_price="5.90" --status=publish \
  --description="Thym citron (<em>Thymus citriodorus</em>) au parfum intense et frais. Plant élevé en serre, <strong>prêt à planter</strong>. Excellent en pot sur balcon ou en pleine terre. Livraison avec fiche culturale." \
  --short_description="<strong>Thymus citriodorus</strong> · Plant prêt à planter · Pot biodégradable" \
  --categories='[{"id":'"$(wc_cat_id "Plants")"'}]' \
  --manage_stock=true --stock_quantity=25 --user="$WP_ADMIN"

step "Adding Épicerie dropdown submenu..."
for cat_name in "Graines" "Plants"; do
  term_id=$($WP term list product_cat --name="$cat_name" --field=term_id --format=ids 2>/dev/null)
  if [ -n "$term_id" ]; then
    $WP menu item add-term "Menu principal" product_cat "$term_id" \
      --parent-id="$EPICERIE_ITEM" \
      --title="$cat_name"
  fi
done
