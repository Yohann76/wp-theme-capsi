<?php
defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$permalink = get_permalink();
$name      = $product->get_name();
$image_id  = $product->get_image_id();
$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' ) : '';
$cats      = get_the_terms( $product->get_id(), 'product_cat' );
?>
<li <?php wc_product_class( 'product-card', $product ); ?>>

	<a href="<?php echo esc_url( $permalink ); ?>" class="product-card__image-link" tabindex="-1" aria-hidden="true">
		<div class="product-card__image-wrap">
			<?php if ( $image_url ) : ?>
				<img src="<?php echo esc_url( $image_url ); ?>"
				     alt="<?php echo esc_attr( $name ); ?>"
				     class="product-card__image"
				     loading="lazy">
			<?php else : ?>
				<div class="product-card__placeholder">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" aria-hidden="true">
						<circle cx="12" cy="8" r="3"/><path d="M6.5 20c0-3 2.5-5 5.5-5s5.5 2 5.5 5"/>
						<path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2z"/>
					</svg>
				</div>
			<?php endif; ?>
		</div>
	</a>

	<div class="product-card__body">

		<?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
			<div class="product-card__cats">
				<span class="product-card__cat"><?php echo esc_html( $cats[0]->name ); ?></span>
			</div>
		<?php endif; ?>

		<h2 class="product-card__title">
			<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $name ); ?></a>
		</h2>

		<div class="product-card__price">
			<?php echo $product->get_price_html(); // phpcs:ignore ?>
		</div>

		<div class="product-card__footer">
			<?php woocommerce_template_loop_add_to_cart(); ?>
		</div>

	</div>
</li>
