#!/usr/bin/env bash
set -euo pipefail

DIR="$(cd "$(dirname "$0")" && pwd)"

# ============================================================
# Configuration
# ============================================================
WP_URL="http://51.178.80.14:8080"
WP_TITLE="L'Atelier Capsi"
WP_ADMIN="admin"
WP_PASS="capsi2024"
WP_EMAIL="yohanndurand76@gmail.com"

WP="docker compose run --rm wpcli"

# ============================================================
# Variables d'environnement (.env non versionné)
# ============================================================
if [ -f "$DIR/../.env" ]; then
    set -a
    source "$DIR/../.env"
    set +a
fi

# ============================================================
# Helpers + wait
# ============================================================
source "$DIR/lib/helpers.sh"
source "$DIR/lib/wait.sh"

# ============================================================
# Setup — infrastructure WordPress
# ============================================================
source "$DIR/setup/01-core.sh"
source "$DIR/setup/02-theme.sh"
source "$DIR/setup/03-menus.sh"
source "$DIR/setup/04-woocommerce.sh"
source "$DIR/setup/05-blog.sh"
source "$DIR/setup/06-plugins.sh"
source "$DIR/setup/07-plugins-config.sh"
source "$DIR/setup/08-attributes.sh"

# ============================================================
# Content — contenu des pages
# ============================================================
source "$DIR/content/faq.sh"
source "$DIR/content/about.sh"
source "$DIR/content/contact.sh"
source "$DIR/content/legal.sh"

# ============================================================
# Done
# ============================================================
echo ""
echo "✓ WordPress configured successfully!"
echo "  Site    : $WP_URL"
echo "  Shop    : $WP_URL/boutique"
echo "  Admin   : $WP_URL/wp-admin  (ou $WP_URL/portail-capsi/ si WPS Hide Login est actif)"
echo "  Login   : $WP_ADMIN / $WP_PASS"
