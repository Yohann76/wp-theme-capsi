<?php

require_once __DIR__ . '/vendor/autoload.php';

use Timber\Timber;
use Timber\Site;

Timber::init();

function capsi_setup(): void {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption' ] );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    register_nav_menus( [
        'primary'        => __( 'Menu principal', 'capsi-theme' ),
        'footer-produits' => __( 'Footer — Produits', 'capsi-theme' ),
        'footer-infos'    => __( 'Footer — Informations', 'capsi-theme' ),
        'footer-legal'    => __( 'Footer — Légal', 'capsi-theme' ),
    ] );
}
add_action( 'after_setup_theme', 'capsi_setup' );

function capsi_enqueue_assets(): void {
    wp_enqueue_style(
        'capsi-main',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        [],
        (string) filemtime( get_stylesheet_directory() . '/assets/css/main.css' )
    );
}

add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
add_action( 'wp_enqueue_scripts', 'capsi_enqueue_assets' );

// WooCommerce — Localisation française
add_filter( 'woocommerce_currency',           fn() => 'EUR' );
add_filter( 'woocommerce_currency_pos',       fn() => 'right_space' );   // 10,00 €
add_filter( 'woocommerce_price_thousand_sep', fn() => "\u{00A0}" );       // espace insécable
add_filter( 'woocommerce_price_decimal_sep',  fn() => ',' );
add_filter( 'woocommerce_price_num_decimals', fn() => 2 );

add_filter( 'timber/context', function ( array $context ): array {
    $context['site']         = new Site();
    $context['menu']         = Timber::get_menu( 'primary' );
    $context['footer_infos'] = Timber::get_menu( 'footer-infos' );
    $context['footer_legal'] = Timber::get_menu( 'footer-legal' );

    $terms = get_terms( [
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
        'orderby'    => 'menu_order',
        'order'      => 'ASC',
    ] );

    $context['footer_product_cats'] = [];
    if ( ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            $context['footer_product_cats'][] = [
                'name' => $term->name,
                'url'  => get_term_link( $term ),
            ];
        }
    }

    return $context;
} );
