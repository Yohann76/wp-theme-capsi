<?php
/**
 * Fiche botanique — rendu HTML partagé entre l'onglet Identité
 * et toute autre surface qui voudrait l'afficher.
 *
 * Requiert : $product (WC_Product) dans la portée appelante.
 */
defined( 'ABSPATH' ) || exit;

$attrs = capsi_get_product_attrs( $product );
if ( empty( $attrs ) ) {
    return;
}

// Séparer les attributs "force" du reste
$main_attrs  = array_values( array_filter( $attrs, function ( $a ) {
    return ! in_array( $a['key'], [ 'niveau-force' ], true )
        && false === stripos( $a['label'], 'Force (SHU)' );
} ) );
$force_attr  = current( array_filter( $attrs, fn( $a ) => false !== stripos( $a['label'], 'Force (SHU)' ) ) ) ?: null;
$niveau_attr = current( array_filter( $attrs, fn( $a ) => $a['key'] === 'niveau-force' ) ) ?: null;
?>
<div class="product-botanical">
    <h2 class="product-botanical__title">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22V12"/><path d="M5 12c0-5 3.5-9 7-9s7 4 7 9"/><path d="M5 12c3 0 5.5-1.5 7-4 1.5 2.5 4 4 7 4"/></svg>
        Fiche botanique
    </h2>

    <dl class="product-botanical__grid">
        <?php foreach ( $main_attrs as $attr ) :
            $is_latin = false !== stripos( $attr['label'], 'latin' );
        ?>
        <div class="product-botanical__row">
            <dt class="product-botanical__label"><?php echo esc_html( $attr['label'] ); ?></dt>
            <dd class="product-botanical__value<?php echo $is_latin ? ' product-botanical__value--latin' : ''; ?>">
                <?php echo esc_html( $attr['value'] ); ?>
            </dd>
        </div>
        <?php endforeach; ?>

        <?php if ( $force_attr || $niveau_attr ) : ?>
        <div class="product-botanical__row product-botanical__row--separator"></div>
        <?php endif; ?>

        <?php if ( $force_attr ) : ?>
        <div class="product-botanical__row">
            <dt class="product-botanical__label"><?php echo esc_html( $force_attr['label'] ); ?></dt>
            <dd class="product-botanical__value product-botanical__value--shu">
                <?php echo esc_html( $force_attr['value'] ); ?>
            </dd>
        </div>
        <?php endif; ?>

        <?php if ( $niveau_attr ) :
            $scale = capsi_force_level( $niveau_attr['value'] );
        ?>
        <div class="product-botanical__row">
            <dt class="product-botanical__label"><?php echo esc_html( $niveau_attr['label'] ); ?></dt>
            <dd class="product-botanical__value">
                <span class="product-botanical__scale"
                      aria-label="<?php echo esc_attr( $scale['label'] . ', ' . $scale['pips'] . '/5' ); ?>">
                    <?php for ( $i = 1; $i <= 5; $i++ ) :
                        $filled = $i <= $scale['pips'];
                    ?>
                    <span class="product-botanical__pip<?php echo $filled ? ' is-filled' : ''; ?>"
                          <?php if ( $filled ) : ?>style="background-color:<?php echo esc_attr( $scale['color'] ); ?>"<?php endif; ?>
                          aria-hidden="true"></span>
                    <?php endfor; ?>
                    <span class="product-botanical__scale-label"
                          style="color:<?php echo esc_attr( $scale['color'] ); ?>">
                        <?php echo esc_html( $scale['label'] ); ?>
                    </span>
                </span>
            </dd>
        </div>
        <?php endif; ?>
    </dl>
</div>
