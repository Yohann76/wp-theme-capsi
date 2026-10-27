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

// WooCommerce — Désactivation des avis produits
add_filter( 'pre_option_woocommerce_enable_reviews', fn() => 'no' );
add_filter( 'comments_open', function ( bool $open ): bool {
    return is_singular( 'product' ) ? false : $open;
} );

// ============================================================
// WooCommerce — Helpers attributs produit
// ============================================================

function capsi_force_level( string $level ): array {
    return match ( mb_strtolower( trim( $level ) ) ) {
        'doux'      => [ 'pips' => 1, 'color' => '#6AAA78', 'label' => 'Doux'      ],
        'moyen'     => [ 'pips' => 2, 'color' => '#B8C850', 'label' => 'Moyen'     ],
        'fort'      => [ 'pips' => 3, 'color' => '#E8A030', 'label' => 'Fort'      ],
        'très fort' => [ 'pips' => 4, 'color' => '#D05828', 'label' => 'Très fort' ],
        'extrême'   => [ 'pips' => 5, 'color' => '#A82020', 'label' => 'Extrême'   ],
        default     => [ 'pips' => 0, 'color' => '#999',    'label' => $level      ],
    };
}

function capsi_get_product_attrs( WC_Product $product ): array {
    $out = [];
    foreach ( $product->get_attributes() as $attr ) {
        if ( ! $attr->get_visible() ) {
            continue;
        }
        if ( $attr->is_taxonomy() ) {
            $terms = wc_get_product_terms( $product->get_id(), $attr->get_name(), [ 'fields' => 'names' ] );
            $value = implode( ', ', $terms );
            $key   = str_replace( 'pa_', '', $attr->get_name() );
        } else {
            $value = implode( ', ', $attr->get_options() );
            $key   = sanitize_title( $attr->get_name() );
        }
        if ( '' === $value ) {
            continue;
        }
        $out[] = [
            'key'   => $key,
            'label' => wc_attribute_label( $attr->get_name() ),
            'value' => $value,
        ];
    }
    return $out;
}

// Regroupement des attributs en 3 colonnes pour l'onglet Caractéristiques
function capsi_group_attrs( array $attrs ): array {
    $identite  = [ 'nom-latin', 'famille', 'espece-capsicum', 'origine' ];
    $apparence = [ 'couleur-maturite', 'arome-principal', 'force-shu', 'niveau-force' ];
    $besoins   = [ 'contenu', 'saison-de-semis' ];

    $groups = [ 'identite' => [], 'apparence' => [], 'besoins' => [] ];
    foreach ( $attrs as $attr ) {
        if ( in_array( $attr['key'], $identite, true ) ) {
            $groups['identite'][]  = $attr;
        } elseif ( in_array( $attr['key'], $apparence, true ) ) {
            $groups['apparence'][] = $attr;
        } elseif ( in_array( $attr['key'], $besoins, true ) ) {
            $groups['besoins'][]   = $attr;
        }
    }
    return $groups;
}

// ============================================================
// WooCommerce — Produits liés (section séparée)
// ============================================================

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

add_action( 'capsi_related_products', function (): void {
    $related = wc_get_related_products( get_the_ID(), 4 );
    if ( empty( $related ) ) {
        return;
    }
    ?>
    <section class="related-section">
        <div class="container">
            <div class="related-section__head">
                <span class="related-section__eyebrow">À découvrir aussi</span>
                <h2 class="related-section__title">Dans la même veine</h2>
            </div>
            <div class="related-section__grid">
                <?php
                foreach ( $related as $related_id ) {
                    $related_product = wc_get_product( $related_id );
                    if ( ! $related_product ) {
                        continue;
                    }
                    ?>
                    <a href="<?php echo esc_url( $related_product->get_permalink() ); ?>"
                       class="related-card">
                        <div class="related-card__img">
                            <?php echo $related_product->get_image( 'woocommerce_thumbnail', [ 'class' => 'related-card__thumb', 'loading' => 'lazy' ] ); // phpcs:ignore ?>
                        </div>
                        <div class="related-card__body">
                            <p class="related-card__cat">
                                <?php
                                $terms = get_the_terms( $related_id, 'product_cat' );
                                if ( $terms && ! is_wp_error( $terms ) ) {
                                    echo esc_html( $terms[0]->name );
                                }
                                ?>
                            </p>
                            <h3 class="related-card__name"><?php echo esc_html( $related_product->get_name() ); ?></h3>
                            <p class="related-card__price"><?php echo wp_kses_post( $related_product->get_price_html() ); ?></p>
                        </div>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>
    <?php
} );

// ============================================================
// WooCommerce — 4 onglets produit
// ============================================================

add_filter( 'woocommerce_product_tabs', function ( array $tabs ): array {
    unset( $tabs['reviews'], $tabs['additional_information'] );

    if ( isset( $tabs['description'] ) ) {
        $tabs['description']['title']    = "Description du pépiniériste";
        $tabs['description']['priority'] = 10;
    }

    $tabs['caracteristiques'] = [
        'title'    => 'Caractéristiques',
        'priority' => 20,
        'callback' => 'capsi_tab_caracteristiques',
    ];

    $tabs['utilisation'] = [
        'title'    => 'Utilisation',
        'priority' => 30,
        'callback' => 'capsi_tab_utilisation',
    ];

    $tabs['conseils'] = [
        'title'    => 'Conseils',
        'priority' => 40,
        'callback' => 'capsi_tab_conseils',
    ];

    return $tabs;
} );

function capsi_tab_caracteristiques(): void {
    global $product;
    if ( ! $product instanceof WC_Product ) {
        return;
    }
    $template = get_stylesheet_directory() . '/woocommerce/partials/caracteristiques.php';
    if ( file_exists( $template ) ) {
        include $template;
    }
}

function capsi_tab_utilisation(): void {
    global $post;
    $content = get_post_meta( $post->ID, '_capsi_utilisation', true );
    if ( empty( $content ) ) {
        echo '<p class="product-tab__empty">Informations d\'utilisation à venir.</p>';
        return;
    }
    echo '<div class="product-tab-text">' . wp_kses_post( wpautop( $content ) ) . '</div>';
}

function capsi_tab_conseils(): void {
    global $post;
    $content = get_post_meta( $post->ID, '_capsi_conseils', true );
    if ( empty( $content ) ) {
        echo '<p class="product-tab__empty">Conseils de culture à venir.</p>';
        return;
    }
    echo '<div class="product-tab-text">' . wp_kses_post( wpautop( $content ) ) . '</div>';
}

// ============================================================
// WooCommerce — Meta boxes admin (Utilisation + Conseils)
// ============================================================

add_action( 'add_meta_boxes', function (): void {
    foreach ( [
        [ 'id' => 'capsi_utilisation', 'title' => 'Utilisation',         'field' => 'capsi_utilisation', 'nonce' => 'capsi_utilisation_save', 'tab' => 'Utilisation' ],
        [ 'id' => 'capsi_conseils',    'title' => 'Conseils de culture',  'field' => 'capsi_conseils',    'nonce' => 'capsi_conseils_save',    'tab' => 'Conseils'    ],
    ] as $box ) {
        add_meta_box( $box['id'], $box['title'], function ( WP_Post $post ) use ( $box ): void {
            $value = get_post_meta( $post->ID, '_' . $box['field'], true );
            wp_nonce_field( $box['nonce'], $box['field'] . '_nonce' );
            echo '<p style="margin-bottom:6px;color:#666;font-size:12px;">Affiché dans l\'onglet <em>' . esc_html( $box['tab'] ) . '</em>. HTML basique accepté (<code>h3</code>, <code>strong</code>, <code>ul</code>).</p>';
            echo '<textarea name="' . esc_attr( $box['field'] ) . '" rows="8" style="width:100%;">' . esc_textarea( $value ) . '</textarea>';
        }, 'product', 'normal', 'default' );
    }
} );

add_action( 'save_post_product', function ( int $post_id ): void {
    foreach ( [
        [ 'field' => 'capsi_utilisation', 'nonce' => 'capsi_utilisation_save' ],
        [ 'field' => 'capsi_conseils',    'nonce' => 'capsi_conseils_save'    ],
    ] as $f ) {
        if ( isset( $_POST[ $f['field'] . '_nonce' ] )
            && wp_verify_nonce( $_POST[ $f['field'] . '_nonce' ], $f['nonce'] )
            && ! ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
            update_post_meta( $post_id, '_' . $f['field'], wp_kses_post( $_POST[ $f['field'] ] ?? '' ) );
        }
    }
} );

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

        'hero_eyebrow'   => get_theme_mod( 'capsi_hero_eyebrow',  'Pépinière artisanale · Normandie' ),
        'hero_title_1'   => get_theme_mod( 'capsi_hero_title_1',  'Piments & aromatiques' ),
        'hero_title_em'  => get_theme_mod( 'capsi_hero_title_em', "d'exception." ),
        'hero_desc'      => get_theme_mod( 'capsi_hero_desc',     "De la sélection des semences à l'expédition du plant, chaque étape se passe ici. Piments rares et plantes aromatiques élevés à la main. En stock limité. Sans compromis." ),
        'hero_cta1_text' => get_theme_mod( 'capsi_hero_cta1_text', 'Explorer la boutique' ),
        'hero_cta1_url'  => get_theme_mod( 'capsi_hero_cta1_url',  '' ) ?: ( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/boutique' ) ),
        'hero_cta2_text' => get_theme_mod( 'capsi_hero_cta2_text', 'Notre atelier' ),
        'hero_cta2_url'  => get_theme_mod( 'capsi_hero_cta2_url',  '' ) ?: ( ( $ap = get_page_by_path( 'a-propos' ) ) ? get_permalink( $ap->ID ) : home_url( '/a-propos' ) ),

        'ticker_items' => array_values( array_filter( array_map( 'trim', explode( "\n",
            get_theme_mod( 'capsi_ticker_items',
                "Semences sélectionnées à la main\nPlants élevés en serre parisienne\nPiments & aromatiques rares\nStock limité · qualité absolue\nVariétés rares du monde entier\nCulture de A à Z dans l'atelier"
            )
        ) ) ) ),

        'manifesto_eyebrow' => get_theme_mod( 'capsi_manifesto_eyebrow', 'Pourquoi si peu de stock\u{00A0}?' ),
        'manifesto_quote'   => get_theme_mod( 'capsi_manifesto_quote',   '« Parce que nous refusons de sacrifier la qualité à la quantité. »' ),
        'manifesto_text'    => get_theme_mod( 'capsi_manifesto_text',    "Chaque lot est préparé à la main, en petite série, selon les disponibilités de notre serre. Certaines variétés s'épuisent en quelques jours — c'est aussi pour cela qu'elles sont exceptionnelles." ),

        'footer_copyright' => get_theme_mod( 'capsi_footer_copyright', "L'Atelier Capsi — Tous droits réservés" ),
        'footer_tagline'   => get_theme_mod( 'capsi_footer_tagline',    'Pépinière artisanale · piments & aromatiques rares' ),
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

// JSON-LD BreadcrumbList — injecté dans <head> sur articles et produits
add_action( 'wp_head', function (): void {
    $crumbs = [];

    if ( is_singular( 'post' ) ) {
        $blog_url = get_permalink( get_option( 'page_for_posts' ) );
        $crumbs[] = [ home_url( '/' ), 'Accueil' ];
        $crumbs[] = [ $blog_url, 'Journal de l\'Atelier' ];
        $cats = get_the_category();
        if ( $cats ) {
            $crumbs[] = [ get_category_link( $cats[0]->term_id ), $cats[0]->name ];
        }
        $crumbs[] = [ get_permalink(), get_the_title() ];

    } elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
        $shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/boutique' );
        $crumbs[] = [ home_url( '/' ), 'Accueil' ];

        if ( is_shop() ) {
            $crumbs[] = [ $shop_url, 'Épicerie' ];
        } elseif ( is_product_category() ) {
            $crumbs[] = [ $shop_url, 'Épicerie' ];
            $current  = get_queried_object();
            $crumbs[] = [ get_term_link( $current ), $current->name ];
        } elseif ( is_singular( 'product' ) ) {
            $crumbs[] = [ $shop_url, 'Épicerie' ];
            $cats = get_the_terms( get_the_ID(), 'product_cat' );
            if ( $cats && ! is_wp_error( $cats ) ) {
                $crumbs[] = [ get_term_link( $cats[0] ), $cats[0]->name ];
            }
            $crumbs[] = [ get_permalink(), get_the_title() ];
        }
    }

    if ( empty( $crumbs ) ) {
        return;
    }

    $items = [];
    foreach ( $crumbs as $i => [ $url, $name ] ) {
        $items[] = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $name,
            'item'     => $url,
        ];
    }

    echo '<script type="application/ld+json">'
        . wp_json_encode(
            [
                '@context'        => 'https://schema.org',
                '@type'           => 'BreadcrumbList',
                'itemListElement' => $items,
            ],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        )
        . '</script>' . "\n";
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
    $add( 'capsi_hero', 'capsi_hero_eyebrow',   'Eyebrow',                      'Pépinière artisanale · Normandie' );
    $add( 'capsi_hero', 'capsi_hero_title_1',   'Titre ligne 1',                'Piments & aromatiques' );
    $add( 'capsi_hero', 'capsi_hero_title_em',  'Titre accentué (italique)',     "d'exception." );
    $add( 'capsi_hero', 'capsi_hero_desc',      'Description',                  "De la sélection des semences à l'expédition du plant, chaque étape se passe ici. Piments rares et plantes aromatiques élevés à la main. En stock limité. Sans compromis.", 'textarea' );
    $add( 'capsi_hero', 'capsi_hero_cta1_text', 'Bouton principal — texte',     'Explorer la boutique' );
    $add( 'capsi_hero', 'capsi_hero_cta1_url',  'Bouton principal — URL (vide = boutique)', '', 'url' );
    $add( 'capsi_hero', 'capsi_hero_cta2_text', 'Bouton secondaire — texte',    'Notre atelier' );
    $add( 'capsi_hero', 'capsi_hero_cta2_url',  'Bouton secondaire — URL (vide = à propos)', '', 'url' );

    // ---- Ticker ----
    $c->add_section( 'capsi_ticker', [ 'title' => 'Accueil — Ticker', 'panel' => 'capsi_panel', 'priority' => 40 ] );
    $add( 'capsi_ticker', 'capsi_ticker_items', 'Éléments du bandeau (un par ligne)',
        "Semences sélectionnées à la main\nPlants élevés en serre parisienne\nPiments & aromatiques rares\nStock limité · qualité absolue\nVariétés rares du monde entier\nCulture de A à Z dans l'atelier",
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
    $add( 'capsi_footer_section', 'capsi_footer_tagline',   'Tagline',         'Pépinière artisanale · piments & aromatiques rares' );
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
