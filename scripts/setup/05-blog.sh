#!/usr/bin/env bash
# Catégories blog + articles de démonstration

step "Creating blog categories..."
$WP term create category "Actualités"         --description="Les dernières nouvelles de L'Atelier Capsi"
$WP term create category "Culture & Conseils" --description="Guides de culture, conseils et techniques"
$WP term create category "Variétés"           --description="Présentation des variétés rares et de collection"
$WP term create category "Recettes"           --description="Recettes et utilisations culinaires"
$WP term delete category 1

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
