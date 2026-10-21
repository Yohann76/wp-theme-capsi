<?php
/**
 * Login Form
 * @see https://woocommerce.com/document/template-structure/
 * @version 9.9.0
 */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_customer_login_form' );
$registration = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
?>

<div class="account-login-page">
    <div class="account-login-grid<?php echo $registration ? '' : ' account-login-grid--single'; ?>">

        <div class="account-card">
            <h1 class="account-card__title">Se connecter</h1>
            <p class="account-card__sub">Accédez à vos commandes et à votre espace personnel.</p>

            <form class="woocommerce-form woocommerce-form-login login account-form" method="post" novalidate>
                <?php do_action( 'woocommerce_login_form_start' ); ?>

                <div class="form-field">
                    <label for="username">Identifiant ou adresse e-mail <span>*</span></label>
                    <input type="text" name="username" id="username" autocomplete="username"
                           value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"
                           required aria-required="true">
                </div>

                <div class="form-field">
                    <label for="password">Mot de passe <span>*</span></label>
                    <input type="password" name="password" id="password" autocomplete="current-password" required aria-required="true">
                </div>

                <?php do_action( 'woocommerce_login_form' ); ?>

                <div class="account-form__row">
                    <label class="account-form__remember">
                        <input type="checkbox" name="rememberme" id="rememberme" value="forever">
                        <span>Se souvenir de moi</span>
                    </label>
                    <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="account-form__lost">Mot de passe oublié&nbsp;?</a>
                </div>

                <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                <button type="submit" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>" class="account-submit">
                    Se connecter
                </button>

                <?php do_action( 'woocommerce_login_form_end' ); ?>
            </form>
        </div>

        <?php if ( $registration ) : ?>
        <div class="account-card account-card--alt">
            <h2 class="account-card__title">Créer un compte</h2>
            <p class="account-card__sub">Rejoignez L'Atelier Capsi et suivez vos commandes facilement.</p>

            <form method="post" class="woocommerce-form woocommerce-form-register register account-form" <?php do_action( 'woocommerce_register_form_tag' ); ?>>
                <?php do_action( 'woocommerce_register_form_start' ); ?>

                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
                <div class="form-field">
                    <label for="reg_username">Identifiant <span>*</span></label>
                    <input type="text" name="username" id="reg_username" autocomplete="username"
                           value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"
                           required aria-required="true">
                </div>
                <?php endif; ?>

                <div class="form-field">
                    <label for="reg_email">Adresse e-mail <span>*</span></label>
                    <input type="email" name="email" id="reg_email" autocomplete="email"
                           value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"
                           required aria-required="true">
                </div>

                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
                <div class="form-field">
                    <label for="reg_password">Mot de passe <span>*</span></label>
                    <input type="password" name="password" id="reg_password" autocomplete="new-password" required aria-required="true">
                </div>
                <?php else : ?>
                <p class="account-form__hint">Un lien de définition du mot de passe vous sera envoyé par e-mail.</p>
                <?php endif; ?>

                <?php do_action( 'woocommerce_register_form' ); ?>
                <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                <button type="submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>" class="account-submit account-submit--outline">
                    Créer un compte
                </button>

                <?php do_action( 'woocommerce_register_form_end' ); ?>
            </form>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
