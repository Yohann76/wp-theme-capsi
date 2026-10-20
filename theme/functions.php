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

    wp_add_inline_style( 'capsi-main', '
        #site-footer .footer-layout {
            display: grid !important;
            grid-template-columns: 220px 1fr !important;
            gap: 0 !important;
            align-items: start;
            width: 100%;
        }
        #site-footer .footer-brand {
            display: flex !important;
            flex-direction: column !important;
            gap: 1rem;
        }
        #site-footer .footer-brand__identity {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            gap: 0.2rem;
            text-decoration: none;
        }
        #site-footer .footer-brand__identity img {
            flex-shrink: 0;
        }
        #site-footer .footer-brand__name {
            display: flex !important;
            flex-direction: column !important;
            font-family: "Cormorant Garamond", serif !important;
            font-weight: 600 !important;
            color: #EDE8DF !important;
            margin: 0 !important;
            max-width: none !important;
            line-height: 1.15 !important;
        }
        #site-footer .footer-brand__name span:first-child {
            font-size: 0.85rem !important;
            letter-spacing: 0.18em !important;
            text-transform: uppercase !important;
            opacity: 0.75 !important;
        }
        #site-footer .footer-brand__name span:last-child {
            font-size: 1.35rem !important;
            letter-spacing: 0.22em !important;
            text-transform: uppercase !important;
        }
        #site-footer .footer-social {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            gap: 0.75rem !important;
        }
        #site-footer .footer-cols {
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 2rem;
            width: 100%;
        }
        #site-footer .footer-col {
            display: flex !important;
            flex-direction: column !important;
            min-width: 0;
        }
        #site-footer .footer-col__title {
            color: #E39B35 !important;
            font-family: "Cormorant Garamond", serif !important;
            font-size: 1rem !important;
            font-weight: 600 !important;
            letter-spacing: 0.06em !important;
            text-transform: uppercase !important;
            display: block !important;
            margin-bottom: 1rem !important;
            padding-bottom: 0.6rem !important;
            border-bottom: 1px solid rgba(227,155,53,0.2) !important;
        }
        #site-footer .footer-col ul {
            display: flex !important;
            flex-direction: column !important;
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
            gap: 0.75rem;
        }
        #site-footer .footer-bottom {
            margin-top: 0 !important;
            border-top: 1px solid rgba(237, 232, 223, 0.08) !important;
        }
        @media (max-width: 900px) {
            #site-footer .footer-layout {
                grid-template-columns: 1fr !important;
            }
        }
        @media (max-width: 640px) {
            #site-footer .footer-cols {
                grid-template-columns: 1fr 1fr !important;
            }
        }
        @media (max-width: 400px) {
            #site-footer .footer-cols {
                grid-template-columns: 1fr !important;
            }
        }
    ' );
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
    $context['site']            = new Site();
    $context['menu']            = Timber::get_menu( 'primary' );
    $context['footer_produits'] = Timber::get_menu( 'footer-produits' );
    $context['footer_infos']    = Timber::get_menu( 'footer-infos' );
    $context['footer_legal']    = Timber::get_menu( 'footer-legal' );

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
