<?php

use Timber\Timber;

$context = Timber::context();

// Sidebar shop — uniquement sur les pages archive/boutique
$context['is_shop_archive'] = ! is_singular( 'product' );

if ( $context['is_shop_archive'] ) {
    // Retirer le résultat count et le tri de leur position par défaut
    // pour les afficher dans la sidebar à la place
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

    $terms = get_terms( [
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
}

ob_start();
woocommerce_content();
$context['woocommerce_content'] = ob_get_clean();

Timber::render( 'woocommerce.twig', $context );
