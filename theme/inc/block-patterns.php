<?php
// Block Patterns — L'Atelier Capsi
// Les styles sont dans assets/css/blocks.css — aucun style inline ici.

add_action( 'init', function (): void {

    register_block_pattern_category( 'capsi', [
        'label' => "L'Atelier Capsi",
    ] );

    // ================================================================
    // 1 — Section héros mini
    // ================================================================
    register_block_pattern( 'capsi/hero-mini', [
        'title'       => 'Section héros',
        'description' => 'Eyebrow + titre italique + description + bouton CTA',
        'categories'  => [ 'capsi' ],
        'content'     => <<<'PATTERN'
<!-- wp:group {"className":"capsi-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group capsi-section">

<!-- wp:paragraph {"className":"capsi-eyebrow"} -->
<p class="capsi-eyebrow">Sous-titre / Eyebrow</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"className":"capsi-heading"} -->
<h2 class="wp-block-heading capsi-heading">Votre titre principal</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"capsi-text"} -->
<p class="capsi-text">Votre description ici. Quelques phrases pour présenter votre contenu de manière claire et élégante.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"capsi-btn-primary"} -->
<div class="wp-block-button capsi-btn-primary"><a class="wp-block-button__link wp-element-button" href="#">Découvrir →</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

</div>
<!-- /wp:group -->
PATTERN,
    ] );

    // ================================================================
    // 2 — Citation mise en avant
    // ================================================================
    register_block_pattern( 'capsi/quote', [
        'title'       => 'Citation mise en avant',
        'description' => 'Blockquote avec bordure rouge et fond sombre',
        'categories'  => [ 'capsi' ],
        'content'     => <<<'PATTERN'
<!-- wp:group {"className":"capsi-quote-card capsi-section-sm","layout":{"type":"constrained"}} -->
<div class="wp-block-group capsi-quote-card capsi-section-sm">

<!-- wp:paragraph {"className":"capsi-eyebrow"} -->
<p class="capsi-eyebrow">Citation</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"capsi-quote-text"} -->
<p class="capsi-quote-text">« Votre citation ici. Un message fort qui reflète l'identité et les valeurs de votre maison. »</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"capsi-text--sm"} -->
<p class="capsi-text--sm">— Auteur, Titre ou source</p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
PATTERN,
    ] );

    // ================================================================
    // 3 — Deux colonnes texte / image
    // ================================================================
    register_block_pattern( 'capsi/two-columns', [
        'title'       => 'Texte et image',
        'description' => 'Deux colonnes 55/45 — texte à gauche, image à droite',
        'categories'  => [ 'capsi' ],
        'content'     => <<<'PATTERN'
<!-- wp:columns {"verticalAlignment":"center","className":"capsi-section"} -->
<div class="wp-block-columns are-vertically-aligned-center capsi-section">

<!-- wp:column {"width":"55%","verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%">

<!-- wp:paragraph {"className":"capsi-eyebrow"} -->
<p class="capsi-eyebrow">Eyebrow</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"className":"capsi-heading"} -->
<h2 class="wp-block-heading capsi-heading">Titre de votre section</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"capsi-text"} -->
<p class="capsi-text">Premier paragraphe. Décrivez votre histoire, votre démarche, ou les caractéristiques de votre produit avec le soin qu'il mérite.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"capsi-text"} -->
<p class="capsi-text">Second paragraphe. Continuez votre récit avec des détails qui renforcent la valeur de ce que vous proposez.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"capsi-btn-outline"} -->
<div class="wp-block-button capsi-btn-outline"><a class="wp-block-button__link wp-element-button" href="#">En savoir plus</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

</div>
<!-- /wp:column -->

<!-- wp:column {"width":"45%"} -->
<div class="wp-block-column" style="flex-basis:45%">

<!-- wp:image {"sizeSlug":"large","style":{"border":{"radius":"4px"}}} -->
<figure class="wp-block-image size-large" style="border-radius:4px"><img src="" alt=""/></figure>
<!-- /wp:image -->

</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->
PATTERN,
    ] );

    // ================================================================
    // 4 — Bannière promotionnelle
    // ================================================================
    register_block_pattern( 'capsi/promo-banner', [
        'title'       => 'Bannière promotionnelle',
        'description' => 'Fond rouge Capsi, texte et CTA inversé — pour offres et actualités',
        'categories'  => [ 'capsi' ],
        'content'     => <<<'PATTERN'
<!-- wp:group {"className":"capsi-banner capsi-section-sm","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group capsi-banner capsi-section-sm">

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">

<!-- wp:paragraph {"className":"capsi-eyebrow"} -->
<p class="capsi-eyebrow">Offre limitée</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"capsi-heading"} -->
<h3 class="wp-block-heading capsi-heading">Votre titre promotionnel</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"capsi-text"} -->
<p class="capsi-text">Description courte de l'offre ou de la nouveauté.</p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"capsi-btn-inverted"} -->
<div class="wp-block-button capsi-btn-inverted"><a class="wp-block-button__link wp-element-button" href="#">Voir la boutique →</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

</div>
<!-- /wp:group -->
PATTERN,
    ] );

    // ================================================================
    // 5 — Grille d'avantages (3 colonnes)
    // ================================================================
    register_block_pattern( 'capsi/advantages-grid', [
        'title'       => "Grille d'avantages",
        'description' => '3 cartes icône + titre + texte sur fond sombre',
        'categories'  => [ 'capsi' ],
        'content'     => <<<'PATTERN'
<!-- wp:group {"className":"capsi-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group capsi-section">

<!-- wp:group {"className":"capsi-intro","layout":{"type":"constrained"}} -->
<div class="wp-block-group capsi-intro">

<!-- wp:paragraph {"textAlign":"center","className":"capsi-eyebrow"} -->
<p class="has-text-align-center capsi-eyebrow">Nos engagements</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2,"textAlign":"center","className":"capsi-heading"} -->
<h2 class="wp-block-heading has-text-align-center capsi-heading">Ce qui nous distingue</h2>
<!-- /wp:heading -->

</div>
<!-- /wp:group -->

<!-- wp:columns -->
<div class="wp-block-columns">

<!-- wp:column {"className":"capsi-card"} -->
<div class="wp-block-column capsi-card">
<!-- wp:paragraph -->
<p>🌱</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"className":"capsi-heading"} -->
<h3 class="wp-block-heading capsi-heading">Titre avantage 1</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"capsi-text"} -->
<p class="capsi-text">Description de cet avantage en deux phrases claires et directes.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"className":"capsi-card"} -->
<div class="wp-block-column capsi-card">
<!-- wp:paragraph -->
<p>🌶️</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"className":"capsi-heading"} -->
<h3 class="wp-block-heading capsi-heading">Titre avantage 2</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"capsi-text"} -->
<p class="capsi-text">Description de cet avantage en deux phrases claires et directes.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"className":"capsi-card"} -->
<div class="wp-block-column capsi-card">
<!-- wp:paragraph -->
<p>📦</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"className":"capsi-heading"} -->
<h3 class="wp-block-heading capsi-heading">Titre avantage 3</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"capsi-text"} -->
<p class="capsi-text">Description de cet avantage en deux phrases claires et directes.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->
PATTERN,
    ] );
} );
