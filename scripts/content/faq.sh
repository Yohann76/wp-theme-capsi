#!/usr/bin/env bash
# FAQ — blocs Gutenberg wp:details injectés via fichier temporaire dans le volume monté

step "Populating FAQ..."

FAQ_ID=$(page_id "FAQ")

# $DIR est défini dans configure.sh (scripts/) — theme/ est un niveau au-dessus
TMPFILE="$DIR/../theme/.faq_tmp.html"

# Heredoc = pas d'échappement d'apostrophes ni de guillemets
cat > "$TMPFILE" <<'ENDFAQ'
<!-- wp:group {"className":"faq-list","layout":{"type":"constrained"}} -->
<div class="wp-block-group faq-list">

<!-- wp:details -->
<details class="wp-block-details"><summary>Quels sont vos délais de livraison ?</summary><!-- wp:paragraph -->
<p>Nous expédions vos commandes sous 24 à 48h ouvrées. La livraison standard prend ensuite 2 à 4 jours ouvrés en France métropolitaine. Un email de confirmation avec numéro de suivi vous est envoyé dès l'expédition.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Comment conserver mes graines pour garantir leur germination ?</summary><!-- wp:paragraph -->
<p>Conservez vos graines dans un endroit frais, sec et à l'abri de la lumière — idéalement au réfrigérateur dans un sachet hermétique avec un sachet de gel de silice. Dans ces conditions, les graines restent viables 3 à 5 ans. Évitez les variations de température et l'humidité.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Vos plants sont-ils certifiés et traités ?</summary><!-- wp:paragraph -->
<p>Tous nos plants sont élevés en serre sans pesticides ni produits chimiques. Nous pratiquons une culture raisonnée. Les graines proviennent de notre propre sélection ou de semenciers spécialisés de confiance. Aucun traitement systématique n'est appliqué.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Puis-je commander des plants en dehors de la saison de plantation ?</summary><!-- wp:paragraph -->
<p>La disponibilité des plants suit le calendrier horticole (mars à juin principalement). En dehors de cette période, nous proposons uniquement des graines. Abonnez-vous à notre newsletter pour être informé de la prochaine mise en vente de plants.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Comment est calculée la force d'un piment (Scoville) ?</summary><!-- wp:paragraph -->
<p>L'échelle de Scoville mesure la concentration en capsaïcine. Un poivron est à 0 SHU, un jalapeño entre 2 500 et 8 000 SHU, un Habanero autour de 350 000 SHU, et le Carolina Reaper dépasse 2 000 000 SHU. La mesure est réalisée par chromatographie en phase liquide.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Proposez-vous des commandes en gros ou pour professionnels ?</summary><!-- wp:paragraph -->
<p>Oui, nous proposons des tarifs dégressifs pour les professionnels (restaurateurs, épiceries fines, maraîchers). Contactez-nous directement via le formulaire de contact en précisant vos besoins et volumes envisagés.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Quelle est votre politique de retour ?</summary><!-- wp:paragraph -->
<p>Vous disposez de 14 jours après réception pour retourner un article non ouvert dans son emballage d'origine. Les graines et plants ne peuvent être retournés pour des raisons sanitaires. En cas de produit endommagé à la livraison, contactez-nous sous 48h avec photos.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details -->
<details class="wp-block-details"><summary>Livrez-vous à l'international ?</summary><!-- wp:paragraph -->
<p>Nous livrons dans toute l'Union Européenne. Pour les envois de graines hors UE, des restrictions phytosanitaires peuvent s'appliquer selon le pays destinataire. Les plants ne sont expédiés qu'en France métropolitaine.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

</div>
<!-- /wp:group -->
ENDFAQ

# wp eval lit le fichier depuis le container (theme/ est monté)
$WP eval '
$file = get_template_directory() . "/.faq_tmp.html";
$content = file_get_contents( $file );
$page = get_page_by_path( "faq" );
if ( $page && $content ) {
    wp_update_post( [ "ID" => $page->ID, "post_content" => $content, "post_status" => "publish" ] );
    echo "  FAQ updated (ID " . $page->ID . ")\n";
    unlink( $file );
} else {
    echo "  ⚠  FAQ page not found or content empty\n";
}
'

rm -f "$TMPFILE"
