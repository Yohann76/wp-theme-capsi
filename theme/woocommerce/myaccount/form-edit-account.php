<?php
/**
 * Edit account form
 * @see https://woocommerce.com/document/template-structure/
 * @version 11.0.0
 */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' );
?>

<form class="woocommerce-EditAccountForm edit-account account-edit-form" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?>>
    <?php do_action( 'woocommerce_edit_account_form_start' ); ?>

    <h2 class="account-section__title">Informations personnelles</h2>

    <div class="form-row--2col">
        <div class="form-field">
            <label for="account_first_name">Prénom <span>*</span></label>
            <input type="text" class="woocommerce-Input input-text" name="account_first_name" id="account_first_name"
                   autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" required aria-required="true">
        </div>
        <div class="form-field">
            <label for="account_last_name">Nom <span>*</span></label>
            <input type="text" class="woocommerce-Input input-text" name="account_last_name" id="account_last_name"
                   autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" required aria-required="true">
        </div>
    </div>

    <div class="form-field">
        <label for="account_display_name">Nom affiché <span>*</span></label>
        <input type="text" class="woocommerce-Input input-text" name="account_display_name" id="account_display_name"
               value="<?php echo esc_attr( $user->display_name ); ?>" required aria-required="true">
        <small class="account-form__hint"><?php echo wc_reviews_enabled() ? esc_html__( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ) : esc_html__( 'This will be how your name will be displayed in the account section', 'woocommerce' ); ?></small>
    </div>

    <div class="form-field">
        <label for="account_email">Adresse e-mail <span>*</span></label>
        <input type="email" class="woocommerce-Input input-text" name="account_email" id="account_email"
               autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" required aria-required="true">
    </div>

    <?php do_action( 'woocommerce_edit_account_form_fields' ); ?>

    <h2 class="account-section__title">Modifier le mot de passe</h2>

    <div class="form-field">
        <label for="password_current">Mot de passe actuel <small>(laisser vide si inchangé)</small></label>
        <input type="password" class="woocommerce-Input input-text" name="password_current" id="password_current" autocomplete="current-password">
    </div>
    <div class="form-row--2col">
        <div class="form-field">
            <label for="password_1">Nouveau mot de passe</label>
            <input type="password" class="woocommerce-Input input-text" name="password_1" id="password_1" autocomplete="new-password">
        </div>
        <div class="form-field">
            <label for="password_2">Confirmer le nouveau mot de passe</label>
            <input type="password" class="woocommerce-Input input-text" name="password_2" id="password_2" autocomplete="new-password">
        </div>
    </div>

    <?php do_action( 'woocommerce_edit_account_form' ); ?>

    <?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
    <button type="submit" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>" class="account-submit">
        Enregistrer les modifications
    </button>
    <input type="hidden" name="action" value="save_account_details">

    <?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
