#!/usr/bin/env bash
# Pages légales — blocs Gutenberg injectés via fichiers temporaires

step "Populating legal pages..."

# ── Helper ──────────────────────────────────────────────────────────────────
inject_legal() {
    local slug="$1"
    local tmpfile="$DIR/../theme/.legal_tmp.html"

    $WP eval '$file = get_template_directory() . "/.legal_tmp.html"; $content = file_get_contents( $file ); $page = get_page_by_path( "'"$slug"'" ); if ( $page && $content ) { wp_update_post( [ "ID" => $page->ID, "post_content" => $content, "post_status" => "publish" ] ); echo "  Updated: " . $page->post_title . " (ID " . $page->ID . ")\n"; unlink( $file ); } else { echo "  Page not found or content empty: '"$slug"'\n"; }'
    rm -f "$tmpfile"
}

# ── Mentions légales ─────────────────────────────────────────────────────────
cat > "$DIR/../theme/.legal_tmp.html" <<'ENDML'
<!-- wp:paragraph {"className":"legal-updated"} -->
<p class="legal-updated">Dernière mise à jour : janvier 2025</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Éditeur du site</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>L'Atelier Capsi<br>1 rue de l'Atelier, 75000 Paris<br>Email : contact@lateliercapsi.fr<br>Directeur de la publication : L'Atelier Capsi</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Hébergement</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Le site est hébergé par OVH SAS, 2 rue Kellermann, 59100 Roubaix.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Propriété intellectuelle</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>L'ensemble du contenu de ce site (textes, images, photographies, logos) est la propriété exclusive de L'Atelier Capsi et est protégé par les lois françaises et internationales relatives à la propriété intellectuelle. Toute reproduction, même partielle, est strictement interdite sans autorisation préalable écrite.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Cookies</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Le site utilise des cookies strictement nécessaires à son fonctionnement (session, panier). Aucun cookie publicitaire ou de traçage tiers n'est déposé sans votre consentement explicite.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Liens hypertextes</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>L'Atelier Capsi ne peut être tenu responsable du contenu des sites tiers vers lesquels des liens sont proposés.</p>
<!-- /wp:paragraph -->
ENDML
inject_legal "mentions-legales"

# ── CGV ──────────────────────────────────────────────────────────────────────
cat > "$DIR/../theme/.legal_tmp.html" <<'ENDCGV'
<!-- wp:paragraph {"className":"legal-updated"} -->
<p class="legal-updated">Dernière mise à jour : janvier 2025</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 1 — Objet</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Les présentes Conditions Générales de Vente (CGV) régissent les ventes réalisées sur le site lateliercapsi.fr entre L'Atelier Capsi (ci-après « le Vendeur ») et tout acheteur consommateur (ci-après « le Client »).</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 2 — Produits</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Les produits proposés sont des graines, plants et produits alimentaires artisanaux. Les caractéristiques essentielles des produits sont présentées sur chaque fiche produit. Les photographies sont données à titre indicatif et n'ont pas de valeur contractuelle.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 3 — Prix</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Les prix sont indiqués en euros toutes taxes comprises (TTC). Le Vendeur se réserve le droit de modifier ses prix à tout moment, étant entendu que le prix appliqué est celui en vigueur au jour de la commande.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 4 — Commande</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>La commande est validée après confirmation du paiement. Un email récapitulatif est adressé au Client. Le Vendeur se réserve le droit d'annuler toute commande pour motif légitime (rupture de stock imprévue, litige antérieur, etc.).</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 5 — Livraison</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Les commandes sont expédiées sous 24 à 48h ouvrées. Les délais de livraison sont donnés à titre indicatif. Le Vendeur ne peut être tenu responsable des retards imputables au transporteur. Les risques liés au transport sont transférés au Client dès la remise au transporteur.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 6 — Rétractation</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Conformément à l'article L221-18 du Code de la consommation, le Client dispose d'un délai de <strong>14 jours</strong> à compter de la réception pour exercer son droit de rétractation, sans motif. Les denrées périssables (plants, produits frais) et les produits descellés sont exclus du droit de rétractation.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 7 — Garanties légales</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Tous les produits bénéficient de la garantie légale de conformité (articles L217-4 et suivants du Code de la consommation) et de la garantie contre les vices cachés (articles 1641 et suivants du Code civil).</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Article 8 — Litiges</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>En cas de litige, le Client peut recourir à une procédure de médiation conventionnelle ou à tout autre mode alternatif de règlement des litiges. La plateforme européenne de règlement en ligne des litiges est accessible à ec.europa.eu/consumers/odr.</p>
<!-- /wp:paragraph -->
ENDCGV
inject_legal "conditions-generales-de-vente"

# ── Politique de confidentialité ─────────────────────────────────────────────
cat > "$DIR/../theme/.legal_tmp.html" <<'ENDRGPD'
<!-- wp:paragraph {"className":"legal-updated"} -->
<p class="legal-updated">Dernière mise à jour : janvier 2025</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Responsable du traitement</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>L'Atelier Capsi, 1 rue de l'Atelier, 75000 Paris — contact@lateliercapsi.fr</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Données collectées</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Lors d'une commande, nous collectons : nom, prénom, adresse de livraison et de facturation, adresse email, numéro de téléphone. Ces données sont nécessaires au traitement et à la livraison de votre commande.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Finalités du traitement</h2>
<!-- /wp:heading -->
<!-- wp:list -->
<ul class="wp-block-list">
<!-- wp:list-item -->
<li>Traitement et suivi des commandes</li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li>Envoi d'emails transactionnels (confirmation, expédition)</li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li>Newsletter (uniquement avec votre consentement explicite)</li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li>Obligations légales et comptables</li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Durée de conservation</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Les données de commande sont conservées 5 ans à compter de la clôture du contrat (obligations comptables). Les données de compte client sont conservées 3 ans après la dernière activité.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Vos droits</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Conformément au RGPD, vous disposez d'un droit d'accès, de rectification, d'effacement, de limitation, de portabilité et d'opposition. Pour exercer ces droits : contact@lateliercapsi.fr. Vous pouvez également déposer une réclamation auprès de la CNIL (cnil.fr).</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Sécurité</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Les paiements sont traités par un prestataire certifié PCI-DSS. Les données de carte bancaire ne sont jamais stockées sur nos serveurs.</p>
<!-- /wp:paragraph -->
ENDRGPD
inject_legal "politique-de-confidentialite"

# ── Livraison ─────────────────────────────────────────────────────────────────
cat > "$DIR/../theme/.legal_tmp.html" <<'ENDLIV'
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Délais d'expédition</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Toutes les commandes reçues avant <strong>12h</strong> (heure de Paris) les jours ouvrés sont expédiées le jour même. Au-delà, elles partent le lendemain ouvré. En période de forte demande (printemps), comptez 48h supplémentaires.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Modes de livraison</h2>
<!-- /wp:heading -->
<!-- wp:table {"className":"delivery-table"} -->
<figure class="wp-block-table delivery-table"><table><thead><tr><th>Mode</th><th>Délai estimé</th><th>Tarif</th></tr></thead><tbody><tr><td>Colissimo Standard</td><td>2–4 jours ouvrés</td><td>4,90 €</td></tr><tr><td>Colissimo Suivi</td><td>48h ouvrés</td><td>6,90 €</td></tr><tr><td>Chronopost Express</td><td>24h ouvrés</td><td>12,90 €</td></tr><tr><td>Livraison offerte</td><td>—</td><td>dès 49 € d'achat</td></tr></tbody></table></figure>
<!-- /wp:table -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Zones de livraison</h2>
<!-- /wp:heading -->
<!-- wp:list -->
<ul class="wp-block-list">
<!-- wp:list-item -->
<li><strong>France métropolitaine</strong> — tous produits (graines, plants, sauces, coffrets)</li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><strong>Union Européenne</strong> — graines et produits alimentaires uniquement</li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><strong>Hors UE</strong> — non disponible pour le moment</li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Livraison des plants</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Les plants vivants sont expédiés uniquement de <strong>mars à juin</strong>, en colis express sous 24–48h pour limiter le stress des plants. Ils sont emballés individuellement dans du papier kraft et calés avec des copeaux de bois biodégradables.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Suivi de commande</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Un email avec le numéro de suivi vous est envoyé dès l'expédition. Vous pouvez suivre votre colis directement sur le site de La Poste ou Chronopost.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Problème à la livraison</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>En cas de colis endommagé ou de livraison manquée, contactez-nous sous <strong>48h</strong> à contact@lateliercapsi.fr avec photos à l'appui. Nous traitons toutes les réclamations dans les 2 jours ouvrés.</p>
<!-- /wp:paragraph -->
ENDLIV
inject_legal "livraison"
