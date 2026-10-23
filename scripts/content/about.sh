#!/usr/bin/env bash
# Page À propos — blocs Gutenberg injectés via fichier temporaire

step "Populating À propos page..."

TMPFILE="$DIR/../theme/.about_tmp.html"

cat > "$TMPFILE" <<'ENDABOUT'
<!-- wp:group {"className":"about-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group about-section">
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Notre mission</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>L'Atelier Capsi est né d'une passion : préserver et partager les variétés de piments les plus rares et les plus remarquables du monde. Dans un contexte d'appauvrissement de la biodiversité cultivée, nous agissons comme un conservatoire vivant du genre <em>Capsicum</em>.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>Nous sélectionnons, cultivons et propageons des variétés d'exception — certaines menacées de disparition — pour les mettre entre les mains des jardiniers curieux, des cuisiniers passionnés et des collectionneurs.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"about-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group about-section">
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Notre histoire</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Tout a commencé en 2018 avec une dizaine de variétés cultivées en serre. Au fil des saisons, la collection s'est étendue à plus de 200 variétés issues des cinq espèces principales de <em>Capsicum</em>. Aujourd'hui, l'Atelier propose graines, plants et produits artisanaux élaborés à partir de notre propre production.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"about-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group about-section">
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Nos valeurs</h2>
<!-- /wp:heading -->
<!-- wp:list {"className":"about-values"} -->
<ul class="wp-block-list about-values">
<!-- wp:list-item -->
<li><strong>Biodiversité</strong> — Chaque variété que nous cultivons représente un patrimoine génétique unique. Nous refusons de ne proposer que les variétés commerciales.</li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><strong>Traçabilité</strong> — Toutes nos graines sont issues de notre propre production ou de semenciers partenaires identifiés. Zéro intermédiaire anonyme.</li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><strong>Transmission</strong> — Partager le savoir fait partie de notre ADN : guides de culture, conseils personnalisés, blog botanique.</li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><strong>Artisanat</strong> — Nos sauces et condiments sont élaborés en petites séries, à partir de piments cultivés sur place, sans conservateurs.</li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"about-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group about-section">
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Notre collection</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Plus de <strong>200 variétés</strong> répertoriées, issues des cinq espèces du genre <em>Capsicum</em> :</p>
<!-- /wp:paragraph -->
<!-- wp:list {"className":"about-species"} -->
<ul class="wp-block-list about-species">
<!-- wp:list-item -->
<li><em>Capsicum annuum</em> — Jalapeño, Cayenne, Paprika, Piment d'Espelette…</li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><em>Capsicum chinense</em> — Habanero, Carolina Reaper, Ghost Pepper, Scotch Bonnet…</li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><em>Capsicum frutescens</em> — Tabasco, Thai, Malagueta…</li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><em>Capsicum baccatum</em> — Aji Amarillo, Lemon Drop, Bishop's Crown…</li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><em>Capsicum pubescens</em> — Rocoto, Manzano…</li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->
ENDABOUT

$WP eval '
$file = get_template_directory() . "/.about_tmp.html";
$content = file_get_contents( $file );
$page = get_page_by_path( "a-propos" );
if ( $page && $content ) {
    wp_update_post( [ "ID" => $page->ID, "post_content" => $content, "post_status" => "publish" ] );
    echo "  À propos updated (ID " . $page->ID . ")\n";
    unlink( $file );
} else {
    echo "  ⚠  À propos page not found or content empty\n";
}
'

rm -f "$TMPFILE"
