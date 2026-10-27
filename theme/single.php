<?php

use Timber\Timber;

$context         = Timber::context();
$context['post'] = Timber::get_post();

$blog_url = get_permalink( get_option( 'page_for_posts' ) );
$crumbs   = [
    [ 'label' => 'Accueil',             'url' => home_url( '/' ) ],
    [ 'label' => 'Journal de l\'Atelier', 'url' => $blog_url ],
];
$cats = get_the_category( $context['post']->ID );
if ( $cats ) {
    $crumbs[] = [ 'label' => $cats[0]->name, 'url' => get_category_link( $cats[0]->term_id ) ];
}
$crumbs[] = [ 'label' => get_the_title( $context['post']->ID ), 'url' => null ];

$context['breadcrumbs'] = $crumbs;

Timber::render( 'single.twig', $context );
