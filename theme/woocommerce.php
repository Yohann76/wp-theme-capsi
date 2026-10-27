<?php

use Timber\Timber;

$context = Timber::context();

// Sidebar shop — uniquement sur les pages archive/boutique
$context['is_shop_archive'] = ! is_singular( 'product' );

// ── Fil d'Ariane ──────────────────────────────────────────────
$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/boutique' );
$crumbs   = [
    [ 'label' => 'Accueil', 'url' => home_url( '/' ) ],
];

if ( is_shop() ) {
    $crumbs[] = [ 'label' => 'Épicerie', 'url' => null ];
} elseif ( is_product_category() ) {
    $crumbs[] = [ 'label' => 'Épicerie', 'url' => $shop_url ];
    $current  = get_queried_object();
    $crumbs[] = [ 'label' => $current->name, 'url' => null ];
} elseif ( is_singular( 'product' ) ) {
    $crumbs[] = [ 'label' => 'Épicerie', 'url' => $shop_url ];
    $cats = get_the_terms( get_the_ID(), 'product_cat' );
    if ( $cats && ! is_wp_error( $cats ) ) {
        $crumbs[] = [ 'label' => $cats[0]->name, 'url' => get_term_link( $cats[0] ) ];
    }
    $crumbs[] = [ 'label' => get_the_title(), 'url' => null ];
}

$context['breadcrumbs'] = $crumbs;

if ( $context['is_shop_archive'] ) {
    // Retirer le résultat count et le tri de leur position par défaut
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

    // Capturer le select de tri séparément
    ob_start();
    woocommerce_catalog_ordering();
    $context['shop_ordering'] = ob_get_clean();

    // Capturer le résultat count séparément
    ob_start();
    woocommerce_result_count();
    $context['shop_result_count'] = ob_get_clean();

    // Catégories produits
    $terms   = get_terms( [
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
        'orderby'    => 'menu_order',
        'order'      => 'ASC',
    ] );
    $current = get_queried_object();

    $context['shop_cats'] = [];
    if ( ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            $context['shop_cats'][] = [
                'name'    => $term->name,
                'url'     => get_term_link( $term ),
                'count'   => $term->count,
                'current' => ( $current instanceof WP_Term && $current->term_id === $term->term_id ),
            ];
        }
    }

    $context['shop_url']         = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/boutique' );
    $context['shop_is_root']     = is_shop();
    $context['shop_current_cat'] = ( $current instanceof WP_Term ) ? $current->name : null;

    // Plage de prix globale de la boutique (tous produits publiés, indépendant de la catégorie)
    global $wpdb;
    $price_range = $wpdb->get_row(
        "SELECT MIN( CAST( pm.meta_value AS DECIMAL(10,2) ) ) AS min_price,
                MAX( CAST( pm.meta_value AS DECIMAL(10,2) ) ) AS max_price
         FROM {$wpdb->postmeta} pm
         INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
         WHERE pm.meta_key = '_price'
           AND pm.meta_value != ''
           AND pm.meta_value > 0
           AND p.post_type   = 'product'
           AND p.post_status = 'publish'"
    );
    $context['price_floor'] = isset( $price_range->min_price ) ? (int) floor( (float) $price_range->min_price ) : 0;
    $context['price_ceil']  = isset( $price_range->max_price ) ? (int) ceil( (float) $price_range->max_price )  : 100;

    // phpcs:disable WordPress.Security.NonceVerification
    // !empty() plutôt qu'isset() : une clé vide (?min_price=) ne doit pas activer le filtre
    $raw_min = $_GET['min_price'] ?? '';
    $raw_max = $_GET['max_price'] ?? '';

    $context['price_min']       = ( $raw_min !== '' && is_numeric( $raw_min ) ) ? (int) $raw_min : '';
    $context['price_max']       = ( $raw_max !== '' && is_numeric( $raw_max ) ) ? (int) $raw_max : '';
    $context['current_orderby'] = sanitize_text_field( $_GET['orderby'] ?? '' );

    // Un filtre est actif seulement si la valeur est un entier valide et hors des bornes par défaut
    $has_price = ( $context['price_min'] !== '' && $context['price_min'] > $context['price_floor'] )
              || ( $context['price_max'] !== '' && $context['price_max'] < $context['price_ceil'] );

    $context['active_filters'] = [];
    if ( $has_price ) {
        $min_label = $context['price_min'] !== '' ? $context['price_min'] . "\u{00A0}€" : $context['price_floor'] . "\u{00A0}€";
        $max_label = $context['price_max'] !== '' ? $context['price_max'] . "\u{00A0}€" : $context['price_ceil']  . "\u{00A0}€";
        $context['active_filters'][] = [
            'label' => 'Prix : ' . $min_label . ' – ' . $max_label,
            'type'  => 'price',
        ];
    }

    $context['filter_reset_url']   = remove_query_arg( [ 'min_price', 'max_price' ] );
    $context['has_active_filters'] = $has_price;
    // phpcs:enable
}

ob_start();
woocommerce_content();
$context['woocommerce_content'] = ob_get_clean();

Timber::render( 'woocommerce.twig', $context );
