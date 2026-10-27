#!/usr/bin/env bash
# Fonctions utilitaires partagées entre tous les scripts

step() {
  echo ""
  echo ">>> $1"
}

# ID d'une page par son titre
page_id() {
  $WP post list \
    --post_type=page \
    --post_status=publish \
    --title="$1" \
    --field=ID \
    --format=ids
}

# ID d'un article blog par son slug
post_id() {
  $WP post list \
    --post_type=post \
    --post_status=publish \
    --post_name__in="$1" \
    --field=ID \
    --format=ids
}

# ID d'une catégorie blog par son nom
cat_id() {
  $WP term list category \
    --name="$1" \
    --field=term_id \
    --format=ids
}

# ID d'une catégorie WooCommerce par son nom
wc_cat_id() {
  $WP wc product_cat list --format=json --user="$WP_ADMIN" \
    | python3 -c "
import sys, json
cats = json.load(sys.stdin)
name = '$1'
match = [c['id'] for c in cats if c['name'] == name]
print(match[0] if match else '')
"
}

# Mettre à jour le contenu d'une page par son titre
page_content() {
  local title="$1"
  local content="$2"
  local id
  id=$(page_id "$title")
  $WP post update "$id" --post_content="$content"
}
