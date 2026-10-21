<?php
/**
 * My Account page
 * @see https://woocommerce.com/document/template-structure/
 * @version 3.5.0
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="account-layout">
    <aside class="account-sidebar">
        <?php do_action( 'woocommerce_account_navigation' ); ?>
    </aside>
    <div class="account-main">
        <?php do_action( 'woocommerce_account_content' ); ?>
    </div>
</div>
