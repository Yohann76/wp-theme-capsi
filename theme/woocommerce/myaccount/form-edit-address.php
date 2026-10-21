<?php
/**
 * Edit address form
 * @see https://woocommerce.com/document/template-structure/
 * @version 9.3.0
 */
defined( 'ABSPATH' ) || exit;

if ( ! $load_address ) :
    wc_get_template( 'myaccount/my-address.php' );
else :
    $page_title = 'billing' === $load_address ? 'Adresse de facturation' : 'Adresse de livraison';
    do_action( 'woocommerce_before_edit_account_address_form' );
?>

<form class="account-address-form" method="post" novalidate>
    <h2 class="account-section__title">
        <?php echo esc_html( apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ) ); ?>
    </h2>

    <div class="woocommerce-address-fields">
        <?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

        <div class="woocommerce-address-fields__field-wrapper">
            <?php
            foreach ( $address as $key => $field ) {
                woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
            }
            ?>
        </div>

        <?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

        <div class="address-form__footer">
            <?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
            <input type="hidden" name="action" value="edit_address">
            <button type="submit" name="save_address" value="<?php esc_attr_e( 'Save address', 'woocommerce' ); ?>" class="account-submit">
                Enregistrer l'adresse
            </button>
            <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="address-form__cancel">
                Annuler
            </a>
        </div>
    </div>
</form>

<?php
    do_action( 'woocommerce_after_edit_account_address_form' );
endif;
?>
