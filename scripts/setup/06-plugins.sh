#!/usr/bin/env bash
# Plugins tiers — installation et activation uniquement (aucune configuration)

# ---- Mailing ----
step "Installing WP Mail SMTP..."
$WP plugin install wp-mail-smtp --activate

# ---- SEO ----
step "Installing Rank Math SEO..."
$WP plugin install seo-by-rank-math --activate

# ---- Stock & inventaire WooCommerce ----
step "Installing WooCommerce Stock Manager..."
$WP plugin install woocommerce-stock-manager --activate

step "Installing ATUM Inventory Management..."
$WP plugin install atum-stock-manager-for-woocommerce --activate

# ---- Performance serveur ----
step "Installing Nginx Helper..."
$WP plugin install nginx-helper --activate

step "Installing Autoptimize..."
$WP plugin install autoptimize --activate

step "Installing Redis Object Cache..."
$WP plugin install redis-cache --activate

# ---- Images ----
step "Installing EWWW Image Optimizer..."
$WP plugin install ewww-image-optimizer --activate

# ---- Sécurité ----
step "Installing WPS Hide Login..."
$WP plugin install wps-hide-login --activate

# ---- Analytics & tracking ----
step "Installing Site Kit by Google..."
$WP plugin install google-site-kit --activate

# ---- Paiement ----
step "Installing WooPayments..."
$WP plugin install woocommerce-payments --activate

step "Installing WooCommerce PayPal Payments..."
$WP plugin install woocommerce-paypal-payments --activate

# ---- RGPD ----
step "Installing Complianz..."
$WP plugin install complianz-gdpr --activate

# ---- Sauvegardes ----
step "Installing UpdraftPlus..."
$WP plugin install updraftplus --activate
