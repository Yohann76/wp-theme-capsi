#!/usr/bin/env bash
# Installation WordPress + langue + options régionales

step "Installing WordPress..."
$WP core install \
  --url="$WP_URL" \
  --title="$WP_TITLE" \
  --admin_user="$WP_ADMIN" \
  --admin_password="$WP_PASS" \
  --admin_email="$WP_EMAIL" \
  --skip-email

step "Raising PHP memory limits in wp-config.php..."
$WP config set WP_MEMORY_LIMIT '256M' --type=constant
$WP config set WP_MAX_MEMORY_LIMIT '512M' --type=constant

step "Setting language to French..."
$WP language core install fr_FR
$WP site switch-language fr_FR
$WP option update timezone_string "Europe/Paris"
$WP option update date_format "d/m/Y"
$WP option update time_format "H:i"
$WP option update start_of_week 1

step "Removing default content..."
$WP post delete 1 2 --force 2>/dev/null || true
