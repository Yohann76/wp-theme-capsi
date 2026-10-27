#!/usr/bin/env bash
# Attributs produit WooCommerce (fiche botanique)

step "Creating product attributes..."

PHP_SCRIPT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/08-attributes.php"
WP_CONTAINER="$(docker compose ps -q wordpress)"

docker cp "$PHP_SCRIPT" "${WP_CONTAINER}:/var/www/html/capsi-attributes.php"
$WP eval-file /var/www/html/capsi-attributes.php
docker exec "$WP_CONTAINER" rm /var/www/html/capsi-attributes.php
