#!/usr/bin/env bash
# Menus de navigation principal + footer

step "Creating navigation menu..."
$WP menu create "Menu principal"
$WP menu item add-post   "Menu principal" "$(page_id "Accueil")"   --title="Accueil"

# Épicerie — lié à la page Boutique (créée dans 02-theme.sh), ID réutilisé dans 04-woocommerce.sh
EPICERIE_ITEM=$($WP menu item add-post "Menu principal" "$(page_id "Boutique")" --title="Épicerie" --porcelain | grep -E '^[0-9]+$')

$WP menu item add-post   "Menu principal" "$(page_id "Blog")"      --title="Blog"
$WP menu item add-post   "Menu principal" "$(page_id "À propos")"  --title="À propos"
$WP menu item add-post   "Menu principal" "$(page_id "Contact")"   --title="Contact"
$WP menu location assign "Menu principal" primary

step "Creating footer menus..."

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
