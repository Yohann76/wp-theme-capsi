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
    $dir = get_stylesheet_directory();
    $uri = get_stylesheet_directory_uri();

    $sheets = [
        'capsi-main' => 'main.css',
    ];

    if ( is_home() || is_archive() || is_singular( 'post' ) ) {
        $sheets['capsi-blog'] = 'blog.css';
    }

    if ( function_exists( 'is_woocommerce' )
        && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() )
    ) {
        $sheets['capsi-woocommerce'] = 'woocommerce.css';
    }

    if ( is_page() ) {
        $sheets['capsi-pages'] = 'pages.css';
    }

    foreach ( $sheets as $handle => $file ) {
        wp_enqueue_style(
            $handle,
            "{$uri}/assets/css/{$file}",
            $handle === 'capsi-main' ? [] : [ 'capsi-main' ],
            (string) filemtime( "{$dir}/assets/css/{$file}" )
        );
    }
}

add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
add_action( 'wp_enqueue_scripts', 'capsi_enqueue_assets' );

// WooCommerce — Localisation française
add_filter( 'woocommerce_currency',           fn() => 'EUR' );
add_filter( 'woocommerce_currency_pos',       fn() => 'right_space' );   // 10,00 €
add_filter( 'woocommerce_price_thousand_sep', fn() => "\u{00A0}" );       // espace insécable
add_filter( 'woocommerce_price_decimal_sep',  fn() => ',' );
add_filter( 'woocommerce_price_num_decimals', fn() => 2 );

// Formulaire de contact
add_action( 'admin_post_nopriv_capsi_contact', 'capsi_handle_contact' );
add_action( 'admin_post_capsi_contact',        'capsi_handle_contact' );

function capsi_handle_contact(): void {
    if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'capsi_contact' ) ) {
        wp_safe_redirect( wp_get_referer() . '?contact=error' );
        exit;
    }

    $name    = sanitize_text_field( $_POST['contact_name']    ?? '' );
    $email   = sanitize_email(      $_POST['contact_email']   ?? '' );
    $subject = sanitize_text_field( $_POST['contact_subject'] ?? '' );
    $message = sanitize_textarea_field( $_POST['contact_message'] ?? '' );

    if ( ! $name || ! is_email( $email ) || ! $message ) {
        wp_safe_redirect( wp_get_referer() . '?contact=error' );
        exit;
    }

    $to      = get_option( 'admin_email' );
    $subject = sprintf( '[L\'Atelier Capsi] %s — %s', $subject ?: 'Contact', $name );
    $body    = sprintf( "De : %s <%s>\n\n%s", $name, $email, $message );
    $headers = [ 'Content-Type: text/plain; charset=UTF-8', "Reply-To: {$name} <{$email}>" ];

    wp_mail( $to, $subject, $body, $headers );

    wp_safe_redirect( wp_get_referer() . '?contact=success' );
    exit;
}

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

    $context['cart'] = null;
    if ( function_exists( 'WC' ) && isset( WC()->cart ) && WC()->cart ) {
        $context['cart'] = [
            'count' => WC()->cart->get_cart_contents_count(),
            'url'   => wc_get_cart_url(),
        ];
    }

    $account = [ 'logged_in' => is_user_logged_in() ];
    if ( is_user_logged_in() ) {
        $user     = wp_get_current_user();
        $fname    = $user->first_name ?: $user->display_name;
        $lname    = $user->last_name;
        $initials = strtoupper( mb_substr( $fname, 0, 1 ) . ( $lname ? mb_substr( $lname, 0, 1 ) : '' ) );

        $account['display_name'] = $user->display_name;
        $account['initials']     = $initials ?: '?';

        if ( function_exists( 'wc_get_account_endpoint_url' ) ) {
            $account['orders_url']  = wc_get_account_endpoint_url( 'orders' );
            $account['account_url'] = wc_get_account_endpoint_url( 'edit-account' );
            $account['address_url'] = wc_get_account_endpoint_url( 'edit-address' );
        }
        $account['logout_url'] = wp_logout_url( home_url() );
    } else {
        $myaccount             = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
        $account['login_url']    = $myaccount;
        $account['register_url'] = $myaccount;
    }
    $context['account'] = $account;

    return $context;
} );

// Mise à jour du badge panier via les fragments AJAX WooCommerce
add_filter( 'woocommerce_add_to_cart_fragments', function ( array $fragments ): array {
    $count = function_exists( 'WC' ) && isset( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
    $fragments['span.nav-cart__count'] = sprintf(
        '<span class="nav-cart__count%s">%d</span>',
        $count > 0 ? '' : ' nav-cart__count--empty',
        $count
    );
    return $fragments;
} );
