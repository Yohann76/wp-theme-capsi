#!/usr/bin/env bash
# Configuration des plugins — SMTP, cookie banner, SEO, perf, etc.

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

# ---- Complianz GDPR — cookie banner theming ----
step "Configuring Complianz cookie banner..."

$WP eval '
global $wpdb;
$t = $wpdb->prefix . "cmplz_cookiebanners";

$bg      = serialize(["color" => "#0F0F0F",  "border"    => "#1E1E1E"]);
$text    = serialize(["color" => "#EDE8DF",  "hyperlink" => "#E39B35"]);
$toggles = serialize(["background" => "#E39B35", "bullet" => "#0F0F0F", "inactive" => "#444444"]);
$radius  = serialize(["top" => 4, "right" => 4, "bottom" => 4, "left" => 4, "type" => "px"]);
$btn_acc = serialize(["background" => "#E39B35", "border" => "#E39B35", "text" => "#0F0F0F"]);
$btn_den = serialize(["background" => "#1E1E1E", "border" => "#333333", "text" => "#EDE8DF"]);
$btn_set = serialize(["background" => "#161616", "border" => "#333333", "text" => "#EDE8DF"]);
$btn_rad = serialize(["top" => 4, "right" => 4, "bottom" => 4, "left" => 4, "type" => "px"]);

$css = ".cc-banner,.cmplz-cookiebanner{font-family:\"Inter\",sans-serif!important;}"
     . ".cmplz-btn{font-family:\"Inter\",sans-serif!important;font-size:.875rem!important;}"
     . ".cmplz-cookiebanner a{color:#E39B35!important;}";

$rows = $wpdb->get_var("SELECT COUNT(*) FROM $t");
if ($rows > 0) {
    $wpdb->update($t, [
        "position"                     => "bottom",
        "colorpalette_background"      => $bg,
        "colorpalette_text"            => $text,
        "colorpalette_toggles"         => $toggles,
        "colorpalette_border_radius"   => $radius,
        "colorpalette_button_accept"   => $btn_acc,
        "colorpalette_button_deny"     => $btn_den,
        "colorpalette_button_settings" => $btn_set,
        "buttons_border_radius"        => $btn_rad,
        "animation"                    => "none",
        "use_box_shadow"               => 0,
        "use_custom_cookie_css"        => "yes",
        "custom_css"                   => $css,
        "disable_cookiebanner"         => 0,
    ], ["default" => 1]);
    echo "  Banner updated (ID " . $wpdb->get_var("SELECT ID FROM $t WHERE \`default\`=1") . ")\n";
} else {
    echo "  ⚠  No banner found — run Complianz setup wizard once then re-run configure.\n";
}
'
