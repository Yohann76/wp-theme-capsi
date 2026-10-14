<?php

function capsi_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption' ] );

    register_nav_menus( [
        'primary' => __( 'Menu principal', 'capsi-theme' ),
    ] );
}
add_action( 'after_setup_theme', 'capsi_setup' );

function capsi_enqueue_assets() {
    wp_enqueue_style( 'capsi-style', get_stylesheet_uri(), [], '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'capsi_enqueue_assets' );
