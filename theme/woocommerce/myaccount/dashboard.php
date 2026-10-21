<?php
/**
 * My Account Dashboard
 * @see https://woocommerce.com/document/template-structure/
 * @version 4.4.0
 */
defined( 'ABSPATH' ) || exit;
?>

<div class="account-dashboard">
    <div class="account-dashboard__welcome">
        <p class="account-dashboard__greeting">
            Bonjour, <strong><?php echo esc_html( $current_user->display_name ); ?></strong>
        </p>
        <p class="account-dashboard__hint">
            Depuis votre espace personnel, vous pouvez consulter vos commandes récentes, gérer vos adresses de livraison et modifier vos informations de connexion.
        </p>
        <a href="<?php echo esc_url( wc_logout_url() ); ?>" class="account-dashboard__logout">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Se déconnecter
        </a>
    </div>

    <div class="account-shortcuts">
        <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="account-shortcut">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
            <span>Mes commandes</span>
        </a>
        <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="account-shortcut">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <span>Mes adresses</span>
        </a>
        <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="account-shortcut">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            <span>Mon compte</span>
        </a>
    </div>
</div>

<?php
do_action( 'woocommerce_account_dashboard' );
do_action( 'woocommerce_before_my_account' );
do_action( 'woocommerce_after_my_account' );
