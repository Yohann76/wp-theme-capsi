<?php
/**
 * My Addresses
 * @see https://woocommerce.com/document/template-structure/
 * @version 9.3.0
 */
defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
    $get_addresses = apply_filters(
        'woocommerce_my_account_get_addresses',
        [ 'billing' => __( 'Billing address', 'woocommerce' ), 'shipping' => __( 'Shipping address', 'woocommerce' ) ],
        $customer_id
    );
} else {
    $get_addresses = apply_filters(
        'woocommerce_my_account_get_addresses',
        [ 'billing' => __( 'Billing address', 'woocommerce' ) ],
        $customer_id
    );
}

$icons = [
    'billing'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>',
    'shipping' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
];
$labels = [
    'billing'  => 'Facturation',
    'shipping' => 'Livraison',
];
?>

<div class="account-addresses">
    <?php foreach ( $get_addresses as $name => $address_title ) :
        $address = wc_get_account_formatted_address( $name );
    ?>
        <div class="address-card<?php echo $address ? '' : ' address-card--empty'; ?>">
            <div class="address-card__head">
                <span class="address-card__type">
                    <?php echo isset( $icons[ $name ] ) ? $icons[ $name ] : ''; // phpcs:ignore ?>
                    <?php echo isset( $labels[ $name ] ) ? esc_html( $labels[ $name ] ) : esc_html( $address_title ); ?>
                </span>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="address-card__action">
                    <?php if ( $address ) : ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Modifier
                    <?php else : ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Ajouter
                    <?php endif; ?>
                </a>
            </div>

            <div class="address-card__body">
                <?php if ( $address ) : ?>
                    <address><?php echo wp_kses_post( $address ); ?></address>
                <?php else : ?>
                    <p class="address-card__empty">Aucune adresse de <?php echo 'billing' === $name ? 'facturation' : 'livraison'; ?> enregistrée.</p>
                    <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="address-card__add-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Ajouter l'adresse de <?php echo 'billing' === $name ? 'facturation' : 'livraison'; ?>
                    </a>
                <?php endif; ?>
                <?php do_action( 'woocommerce_my_account_after_my_address', $name ); ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
