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
        'primary' => __( 'Menu principal', 'capsi-theme' ),
    ] );
}
add_action( 'after_setup_theme', 'capsi_setup' );

function capsi_enqueue_assets(): void {
    wp_enqueue_style(
        'capsi-main',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        [],
        '1.0.0'
    );
}
add_action( 'wp_enqueue_scripts', 'capsi_enqueue_assets' );

add_filter( 'timber/context', function ( array $context ): array {
    $context['site'] = new Site();
    $context['menu'] = Timber::get_menu( 'primary' );
    return $context;
} );
