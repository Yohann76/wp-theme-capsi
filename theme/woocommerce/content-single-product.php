<?php
defined( 'ABSPATH' ) || exit;

global $product;
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'single-product-wrap', $product ); ?>>

	<?php do_action( 'woocommerce_before_single_product' ); ?>

	<?php if ( post_password_required() ) {
		echo get_the_password_form(); // phpcs:ignore
		return;
	} ?>

	<div class="single-product-layout">

		<div class="single-product__gallery">
			<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
		</div>

		<div class="single-product__summary">
			<?php do_action( 'woocommerce_single_product_summary' ); ?>
		</div>

	</div>

	<div class="single-product__tabs">
		<?php do_action( 'woocommerce_after_single_product_summary' ); ?>
	</div>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
