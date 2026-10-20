#!/usr/bin/env bash
# Activation du thème + pages + permaliens

step "Activating theme..."
$WP theme activate capsi-theme
$WP option update blogdescription ""

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

step "Configuring permalinks..."
$WP option update permalink_structure "/%postname%/"
$WP rewrite flush
