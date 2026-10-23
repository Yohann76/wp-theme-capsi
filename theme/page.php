<?php

use Timber\Timber;

$context         = Timber::context();
$post            = $context['post'] = Timber::get_post();
$slug            = $post ? $post->post_name : '';

// Pages légales — partagent toutes le même template document
$legal_slugs = [
	'mentions-legales',
	'conditions-generales-de-vente',
	'politique-de-confidentialite',
	'livraison',
];

if ( $slug === 'accueil' ) {
	$context['featured_products'] = [];
	if ( function_exists( 'wc_get_products' ) ) {
		$prods = wc_get_products( [ 'limit' => 3, 'status' => 'publish', 'orderby' => 'date', 'order' => 'DESC' ] );
		foreach ( $prods as $p ) {
			$img = get_the_post_thumbnail_url( $p->get_id(), 'woocommerce_thumbnail' );
			$context['featured_products'][] = [
				'title'          => $p->get_name(),
				'url'            => get_permalink( $p->get_id() ),
				'image'          => $img ?: '',
				'price'          => wc_price( $p->get_price() ),
				'regular_price'  => wc_price( $p->get_regular_price() ),
				'on_sale'        => $p->is_on_sale(),
				'cats'           => wp_get_post_terms( $p->get_id(), 'product_cat', [ 'fields' => 'names' ] ),
			];
		}
	}
}


if ( $slug === 'contact' ) {
	$context['contact_email']   = get_post_meta( $post->ID, '_contact_email',   true );
	$context['contact_phone']   = get_post_meta( $post->ID, '_contact_phone',   true );
	$context['contact_address'] = get_post_meta( $post->ID, '_contact_address', true );
	$context['contact_hours']   = get_post_meta( $post->ID, '_contact_hours',   true );
	$context['contact_nonce']   = wp_create_nonce( 'capsi_contact' );
	$context['contact_status']  = sanitize_key( $_GET['contact'] ?? '' );
}

if ( in_array( $slug, $legal_slugs, true ) ) {
	Timber::render( 'page-legal.twig', $context );
} else {
	Timber::render( [ "page-{$slug}.twig", 'page.twig' ], $context );
}
