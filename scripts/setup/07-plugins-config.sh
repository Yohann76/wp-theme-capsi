#!/usr/bin/env bash
# Configuration des plugins — paramètres spécifiques à renseigner ici (SMTP, URL cachée, Redis, Nginx, etc.)

# ---- WPS Hide Login ----
step "Configuring WPS Hide Login..."
$WP option update whl_page "portail-capsi" # option réelle du plugin — wps_hide_login_login est incorrecte
$WP rewrite flush --hard
