#!/usr/bin/env bash
# Configuration des plugins — paramètres spécifiques à renseigner ici (SMTP, URL cachée, Redis, Nginx, etc.)

# ---- WPS Hide Login ----
step "Configuring WPS Hide Login..."
$WP option update whl_page "portail-capsi" # option réelle du plugin — wps_hide_login_login est incorrecte
$WP rewrite flush --hard

# ---- WP Mail SMTP ----
step "Configuring WP Mail SMTP..."

# Valeurs statiques (non secrètes) — surchargeables via .env
: "${SMTP_FROM:=contact@latelierscapsi.fr}"
: "${SMTP_FROM_NAME:=L Atelier Capsi}"
: "${SMTP_PORT:=587}"
: "${SMTP_ENCRYPTION:=tls}"

# Les valeurs secrètes (SMTP_HOST, SMTP_USER, SMTP_PASS) viennent du .env
if [ -z "${SMTP_HOST:-}" ]; then
    echo "  ⚠  SMTP_HOST non défini — WP Mail SMTP configuré sans serveur. Renseigner .env et relancer."
fi

$WP option update wp_mail_smtp \
  "{\"general\":{\"from_email\":\"${SMTP_FROM}\",\"from_name\":\"${SMTP_FROM_NAME}\",\"mailer\":\"smtp\",\"do_not_send\":false,\"return_path\":true},\"smtp\":{\"host\":\"${SMTP_HOST:-}\",\"port\":${SMTP_PORT},\"encryption\":\"${SMTP_ENCRYPTION}\",\"auth\":true,\"user\":\"${SMTP_USER:-}\",\"pass\":\"${SMTP_PASS:-}\",\"autotls\":true}}" \
  --format=json
