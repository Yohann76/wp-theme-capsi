<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/block-patterns.php';

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
    add_theme_support( 'editor-color-palette', [
        [ 'name' => 'Rouge Capsi',        'slug' => 'rouge-capsi',        'color' => '#C82A2B' ],
        [ 'name' => 'Vert Botaniq',       'slug' => 'vert-botaniq',       'color' => '#2D4A3E' ],
        [ 'name' => 'Terre de Semis',     'slug' => 'terre-de-semis',     'color' => '#B85C38' ],
        [ 'name' => 'Safran Brûlé',       'slug' => 'safran-brule',       'color' => '#E39B35' ],
        [ 'name' => 'Papier Botanique',   'slug' => 'papier-botanique',   'color' => '#F4EFE6' ],
        [ 'name' => 'Fond sombre',        'slug' => 'fond-sombre',        'color' => '#0F0F0F' ],
        [ 'name' => 'Surface sombre',     'slug' => 'surface-sombre',     'color' => '#1E1E1E' ],
        [ 'name' => 'Texte clair',        'slug' => 'texte-clair',        'color' => '#EDE8DF' ],
        [ 'name' => 'Texte secondaire',   'slug' => 'texte-secondaire',   'color' => '#A8A09A' ],
    ] );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/blocks.css' );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ] );

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
        'capsi-main'   => 'main.css',
        'capsi-blocks' => 'blocks.css',
    ];

    if ( is_home() || is_archive() || is_singular( 'post' ) || is_search() ) {
        $sheets['capsi-blog'] = 'blog.css';
    }

    if ( function_exists( 'is_woocommerce' )
        && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() )
    ) {
        $sheets['capsi-woocommerce'] = 'woocommerce.css';
    }

    if ( is_front_page() ) {
        $sheets['capsi-home'] = 'home.css';
    }

    if ( is_page() ) {
        $sheets['capsi-pages'] = 'pages.css';
    }

    foreach ( $sheets as $handle => $file ) {
        wp_enqueue_style(
            $handle,
            "{$uri}/assets/css/{$file}",
            in_array( $handle, [ 'capsi-main', 'capsi-blocks' ], true ) ? [] : [ 'capsi-main' ],
            (string) filemtime( "{$dir}/assets/css/{$file}" )
        );
    }

    wp_enqueue_script(
        'capsi-main',
        "{$uri}/assets/js/main.js",
        [],
        (string) filemtime( "{$dir}/assets/js/main.js" ),
        true
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

    $context['capsi'] = [
        'logo_html'     => has_custom_logo() ? get_custom_logo() : null,
        'has_site_icon' => has_site_icon(),

        'address'  => get_theme_mod( 'capsi_address',  '' ),
        'phone'    => get_theme_mod( 'capsi_phone',    '' ),
        'email'    => get_theme_mod( 'capsi_email',    '' ),
        'hours'    => get_theme_mod( 'capsi_hours',   '' ),
        'maps_url' => get_theme_mod( 'capsi_maps_url', '' ),

        'instagram' => get_theme_mod( 'capsi_instagram', '' ),
        'facebook'  => get_theme_mod( 'capsi_facebook',  '' ),
        'tiktok'    => get_theme_mod( 'capsi_tiktok',    '' ),

        'hero_eyebrow'   => get_theme_mod( 'capsi_hero_eyebrow',  'Atelier artisanal · Paris' ),
        'hero_title_1'   => get_theme_mod( 'capsi_hero_title_1',  "L'art du" ),
        'hero_title_em'  => get_theme_mod( 'capsi_hero_title_em', 'piment rare.' ),
        'hero_desc'      => get_theme_mod( 'capsi_hero_desc',     "De la sélection des semences à la mise en bocal, chaque création naît dans notre atelier. Élevé à la main. En stock limité. Sans compromis." ),
        'hero_cta1_text' => get_theme_mod( 'capsi_hero_cta1_text', 'Explorer la boutique' ),
        'hero_cta1_url'  => get_theme_mod( 'capsi_hero_cta1_url',  '' ) ?: ( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/boutique' ) ),
        'hero_cta2_text' => get_theme_mod( 'capsi_hero_cta2_text', 'Notre atelier' ),
        'hero_cta2_url'  => get_theme_mod( 'capsi_hero_cta2_url',  '' ) ?: ( ( $ap = get_page_by_path( 'a-propos' ) ) ? get_permalink( $ap->ID ) : home_url( '/a-propos' ) ),

        'ticker_items' => array_values( array_filter( array_map( 'trim', explode( "\n",
            get_theme_mod( 'capsi_ticker_items',
                "Semences sélectionnées à la main\nPlants élevés en serre parisienne\nSauces sans conservateurs\nStock limité · qualité absolue\nVariétés rares du monde entier\nConfection de A à Z dans l'atelier"
            )
        ) ) ) ),

        'manifesto_eyebrow' => get_theme_mod( 'capsi_manifesto_eyebrow', 'Pourquoi si peu de stock\u{00A0}?' ),
        'manifesto_quote'   => get_theme_mod( 'capsi_manifesto_quote',   '« Parce que nous refusons de sacrifier la qualité à la quantité. »' ),
        'manifesto_text'    => get_theme_mod( 'capsi_manifesto_text',    "Chaque lot est préparé à la main, en petite série, selon les disponibilités de notre serre. Certaines variétés s'épuisent en quelques jours — c'est aussi pour cela qu'elles sont exceptionnelles." ),

        'footer_copyright' => get_theme_mod( 'capsi_footer_copyright', "L'Atelier Capsi — Tous droits réservés" ),
        'footer_tagline'   => get_theme_mod( 'capsi_footer_tagline',    'Conservatoire des piments rares & de collection' ),
    ];

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
        $account['register_url'] = add_query_arg( 'register', '1', $myaccount );
    }
    $context['account'] = $account;

    return $context;
} );

// Filtre Twig nl2br (utilisé pour l'adresse footer)
add_filter( 'timber/twig', function ( \Twig\Environment $twig ) {
    $twig->addFilter( new \Twig\TwigFilter( 'nl2br', function ( string $str ): string {
        return nl2br( esc_html( $str ) );
    }, [ 'is_safe' => [ 'html' ] ] ) );
    return $twig;
} );

// ============================================================
// Customizer — paramètres éditables depuis WP Admin
// ============================================================
add_action( 'customize_register', function ( WP_Customize_Manager $c ): void {

    $c->add_panel( 'capsi_panel', [
        'title'    => "L'Atelier Capsi",
        'priority' => 30,
    ] );

    $add = function ( string $section, string $id, string $label, string $default, string $type = 'text' ) use ( $c ): void {
        $sanitize = match ( $type ) {
            'url'      => 'esc_url_raw',
            'textarea' => 'sanitize_textarea_field',
            default    => 'sanitize_text_field',
        };
        $c->add_setting( $id, [ 'default' => $default, 'sanitize_callback' => $sanitize, 'transport' => 'refresh' ] );
        $c->add_control( $id, [ 'label' => $label, 'section' => $section, 'type' => $type ] );
    };

    // ---- Coordonnées ----
    $c->add_section( 'capsi_contact', [ 'title' => 'Coordonnées', 'panel' => 'capsi_panel', 'priority' => 10 ] );
    $add( 'capsi_contact', 'capsi_address',  'Adresse (une ligne par retour)',  '', 'textarea' );
    $add( 'capsi_contact', 'capsi_phone',    'Téléphone',                       '', 'text'     );
    $add( 'capsi_contact', 'capsi_email',    'E-mail de contact',               '', 'text'     );
    $add( 'capsi_contact', 'capsi_hours',    'Horaires',                        '', 'text'     );
    $add( 'capsi_contact', 'capsi_maps_url', 'Lien Google Maps',                '', 'url'      );

    // ---- Réseaux sociaux ----
    $c->add_section( 'capsi_social', [ 'title' => 'Réseaux sociaux', 'panel' => 'capsi_panel', 'priority' => 20 ] );
    $add( 'capsi_social', 'capsi_instagram', 'Instagram', '', 'url' );
    $add( 'capsi_social', 'capsi_facebook',  'Facebook',  '', 'url' );
    $add( 'capsi_social', 'capsi_tiktok',    'TikTok',    '', 'url' );

    // ---- Hero ----
    $c->add_section( 'capsi_hero', [ 'title' => 'Accueil — Hero', 'panel' => 'capsi_panel', 'priority' => 30 ] );
    $add( 'capsi_hero', 'capsi_hero_eyebrow',   'Eyebrow',                      'Atelier artisanal · Paris' );
    $add( 'capsi_hero', 'capsi_hero_title_1',   'Titre ligne 1',                "L'art du" );
    $add( 'capsi_hero', 'capsi_hero_title_em',  'Titre accentué (italique)',     'piment rare.' );
    $add( 'capsi_hero', 'capsi_hero_desc',      'Description',                  "De la sélection des semences à la mise en bocal, chaque création naît dans notre atelier. Élevé à la main. En stock limité. Sans compromis.", 'textarea' );
    $add( 'capsi_hero', 'capsi_hero_cta1_text', 'Bouton principal — texte',     'Explorer la boutique' );
    $add( 'capsi_hero', 'capsi_hero_cta1_url',  'Bouton principal — URL (vide = boutique)', '', 'url' );
    $add( 'capsi_hero', 'capsi_hero_cta2_text', 'Bouton secondaire — texte',    'Notre atelier' );
    $add( 'capsi_hero', 'capsi_hero_cta2_url',  'Bouton secondaire — URL (vide = à propos)', '', 'url' );

    // ---- Ticker ----
    $c->add_section( 'capsi_ticker', [ 'title' => 'Accueil — Ticker', 'panel' => 'capsi_panel', 'priority' => 40 ] );
    $add( 'capsi_ticker', 'capsi_ticker_items', 'Éléments du bandeau (un par ligne)',
        "Semences sélectionnées à la main\nPlants élevés en serre parisienne\nSauces sans conservateurs\nStock limité · qualité absolue\nVariétés rares du monde entier\nConfection de A à Z dans l'atelier",
        'textarea'
    );

    // ---- Manifeste ----
    $c->add_section( 'capsi_manifesto', [ 'title' => 'Accueil — Manifeste', 'panel' => 'capsi_panel', 'priority' => 50 ] );
    $add( 'capsi_manifesto', 'capsi_manifesto_eyebrow', 'Eyebrow',     'Pourquoi si peu de stock ?' );
    $add( 'capsi_manifesto', 'capsi_manifesto_quote',   'Citation',    '« Parce que nous refusons de sacrifier la qualité à la quantité. »', 'textarea' );
    $add( 'capsi_manifesto', 'capsi_manifesto_text',    'Paragraphe',  "Chaque lot est préparé à la main, en petite série, selon les disponibilités de notre serre. Certaines variétés s'épuisent en quelques jours — c'est aussi pour cela qu'elles sont exceptionnelles.", 'textarea' );

    // ---- Footer ----
    $c->add_section( 'capsi_footer_section', [ 'title' => 'Footer', 'panel' => 'capsi_panel', 'priority' => 60 ] );
    $add( 'capsi_footer_section', 'capsi_footer_copyright', 'Texte copyright', "L'Atelier Capsi — Tous droits réservés" );
    $add( 'capsi_footer_section', 'capsi_footer_tagline',   'Tagline',         'Conservatoire des piments rares & de collection' );
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
