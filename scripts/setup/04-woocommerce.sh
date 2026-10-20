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
$WP option update woocommerce_enable_reviews       "yes"
$WP option update woocommerce_catalog_columns      "3"
$WP option update woocommerce_catalog_rows         "4"

step "Creating WooCommerce pages..."
$WP wc tool run install_pages --user="$WP_ADMIN"

step "Creating product categories..."
$WP wc product_cat create \
  --name="Graines" \
  --description="Graines de piments rares et de collection" \
  --user="$WP_ADMIN"

$WP wc product_cat create \
  --name="Plants" \
  --description="Plants de piments prêts à planter" \
  --user="$WP_ADMIN"

$WP wc product_cat create \
  --name="Sauces et Condiments" \
  --description="Sauces artisanales et condiments" \
  --user="$WP_ADMIN"

$WP wc product_cat create \
  --name="Coffrets" \
  --description="Coffrets cadeaux et collections" \
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
  --description="L'Habanero Orange est un classique fruité et intense. Notes tropicales, <strong>350 000 Scoville</strong>. Parfait pour débuter la collection ou préparer sauces et marinades." \
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
  --name="Sauce artisanale Habanero & Mangue" \
  --type=simple --regular_price="9.90" --status=publish \
  --description="Sauce artisanale élaborée à partir de nos piments Habanero cultivés à l'Atelier, associés à la mangue. <strong>Flacon verre ambré 100ml</strong>. Aucun conservateur ajouté." \
  --short_description="<strong>Flacon 100ml</strong> · Artisanal · Habanero & Mangue" \
  --categories='[{"id":'"$(wc_cat_id "Sauces et Condiments")"'}]' \
  --manage_stock=true --stock_quantity=30 --user="$WP_ADMIN"

$WP wc product create \
  --name="Coffret Découverte — 5 variétés rares" \
  --type=simple --regular_price="24.90" --sale_price="19.90" --status=publish \
  --description="Coffret de <strong>5 sachets de graines rares</strong> : Carolina Reaper, Ghost Pepper, Habanero Chocolate, Moruga Scorpion et Aji Amarillo. Présenté dans une boîte kraft avec fiches descriptives. Idéal en cadeau." \
  --short_description="<strong>5 variétés rares</strong> · Édition limitée · Idéal cadeau" \
  --categories='[{"id":'"$(wc_cat_id "Coffrets")"'}]' \
  --manage_stock=true --stock_quantity=20 --user="$WP_ADMIN"
