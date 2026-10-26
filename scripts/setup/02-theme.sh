#!/usr/bin/env bash
# Activation du thème + pages + permaliens

step "Activating theme..."
$WP theme activate capsi-theme
$WP option update blogdescription ""

step "Creating pages..."
$WP post create --post_type=page --post_status=publish --post_title="Accueil"
$WP post create --post_type=page --post_status=publish --post_title="Boutique"  --post_name="boutique"
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

step "Configuring permalinks..."
$WP option update permalink_structure "/%postname%/"
$WP rewrite flush

step "Setting theme customizer defaults..."
$WP theme mod set capsi_hero_eyebrow   "Pépinière artisanale · Normandie"
$WP theme mod set capsi_hero_title_1   "Piments & aromatiques"
$WP theme mod set capsi_hero_title_em  "d'exception."
$WP theme mod set capsi_hero_desc      "De la sélection des semences à l'expédition du plant, chaque étape se passe ici. Piments rares et plantes aromatiques élevés à la main. En stock limité. Sans compromis."
$WP theme mod set capsi_hero_cta1_text "Explorer la boutique"
$WP theme mod set capsi_hero_cta2_text "Notre atelier"
$WP theme mod set capsi_manifesto_eyebrow "Pourquoi si peu de stock ?"
$WP theme mod set capsi_manifesto_quote   "« Parce que nous refusons de sacrifier la qualité à la quantité. »"
$WP theme mod set capsi_manifesto_text    "Chaque lot est préparé à la main, en petite série, selon les disponibilités de notre serre. Certaines variétés s'épuisent en quelques jours — c'est aussi pour cela qu'elles sont exceptionnelles."
$WP theme mod set capsi_footer_tagline "Pépinière artisanale · piments & aromatiques rares"
$WP theme mod set capsi_footer_copyright "L'Atelier Capsi — Tous droits réservés"
