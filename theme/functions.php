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
        '1.0.0'
    );

    wp_add_inline_style( 'capsi-main', '
        #site-footer .footer-layout {
            display: grid !important;
            grid-template-columns: 240px 1fr !important;
            gap: 4rem;
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
            gap: 0.75rem;
            text-decoration: none;
        }
        #site-footer .footer-brand__identity img {
            flex-shrink: 0;
            filter: brightness(0) invert(1);
        }
        #site-footer .footer-brand__name {
            font-family: "Cormorant Garamond", serif !important;
            font-size: 1.25rem !important;
            font-weight: 600 !important;
            color: #EDE8DF !important;
            margin: 0 !important;
            max-width: none !important;
            line-height: 1.2 !important;
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
            margin-top: 3rem !important;
            border-top: none !important;
            position: relative;
        }
        #site-footer .footer-bottom::before {
            content: "" !important;
            display: block !important;
            width: 80px !important;
            height: 1px !important;
            background-color: rgba(227, 155, 53, 0.5) !important;
            margin: 0 auto 2rem !important;
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

add_filter( 'timber/context', function ( array $context ): array {
    $context['site']           = new Site();
    $context['menu']           = Timber::get_menu( 'primary' );
    $context['footer_produits'] = Timber::get_menu( 'footer-produits' );
    $context['footer_infos']    = Timber::get_menu( 'footer-infos' );
    $context['footer_legal']    = Timber::get_menu( 'footer-legal' );
    return $context;
} );
