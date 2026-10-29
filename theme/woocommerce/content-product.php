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
			<?php if ( ! $product->is_in_stock() ) : ?>
				<div class="product-card__oos-badge">Rupture de stock</div>
			<?php endif; ?>
			<?php if ( $image_url ) : ?>
				<img src="<?php echo esc_url( $image_url ); ?>"
				     alt="<?php echo esc_attr( $name ); ?>"
				     class="product-card__image"
				     loading="lazy">
			<?php else : ?>
				<div class="product-card__placeholder">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 96" width="56" height="56" aria-hidden="true" class="product-card__placeholder-icon">
						<defs>
							<linearGradient id="ph-prod-g" x1="0%" y1="100%" x2="0%" y2="0%">
								<stop offset="0%" stop-color="#3A6848" stop-opacity="0.65"/>
								<stop offset="100%" stop-color="#7ABB8A" stop-opacity="0.45"/>
							</linearGradient>
						</defs>
						<path d="M 40,92 C 40,76 40,62 40,50" stroke="url(#ph-prod-g)" stroke-width="3.5" fill="none" stroke-linecap="round"/>
						<path d="M 40,50 C 33,38 23,24 14,11" stroke="url(#ph-prod-g)" stroke-width="3" fill="none" stroke-linecap="round"/>
						<path d="M 40,50 C 47,38 57,24 66,11" stroke="url(#ph-prod-g)" stroke-width="3" fill="none" stroke-linecap="round"/>
						<ellipse cx="24" cy="33" rx="8" ry="3.5" fill="#6AAA78" opacity="0.55" transform="rotate(-42 24 33)"/>
						<ellipse cx="56" cy="33" rx="8" ry="3.5" fill="#6AAA78" opacity="0.55" transform="rotate(42 56 33)"/>
						<circle cx="40" cy="50" r="3.5" fill="#B8832A" opacity="0.6"/>
						<ellipse cx="14" cy="11" rx="3.5" ry="5.5" fill="#B8832A" opacity="0.55" transform="rotate(-12 14 11)"/>
						<circle cx="66" cy="13" r="4" fill="#7ABB8A" opacity="0.55"/>
						<circle cx="61" cy="8"  r="3" fill="#7ABB8A" opacity="0.45"/>
						<circle cx="71" cy="8"  r="3" fill="#7ABB8A" opacity="0.45"/>
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
			<?php if ( $product->is_in_stock() ) : ?>
				<?php woocommerce_template_loop_add_to_cart(); ?>
			<?php else : ?>
				<span class="product-card__oos-label">Indisponible</span>
			<?php endif; ?>
		</div>

	</div>
</li>
