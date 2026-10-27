<?php
/**
 * Onglet Caractéristiques — 3 colonnes blanches
 * Son identité / Son apparence / Ses besoins
 *
 * Requiert : $product (WC_Product) dans la portée appelante.
 */
defined( 'ABSPATH' ) || exit;

$attrs  = capsi_get_product_attrs( $product );
$groups = capsi_group_attrs( $attrs );

$columns = [
    'identite'  => [
        'title' => 'Son identité',
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22V12"/><path d="M5 12c0-5 3.5-9 7-9s7 4 7 9"/><path d="M5 12c3 0 5.5-1.5 7-4 1.5 2.5 4 4 7 4"/></svg>',
    ],
    'apparence' => [
        'title' => 'Son apparence',
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M3 12h1m16 0h1M12 3v1m0 16v1m-6.36-2.64.71-.71m11.31-11.31.71-.71M5.64 5.64l-.71-.71m12.73 12.73-.71-.71"/></svg>',
    ],
    'besoins'   => [
        'title' => 'Ses besoins',
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2a10 10 0 0 1 10 10c0 5.52-4.48 10-10 10S2 17.52 2 12"/><path d="M12 6v6l4 2"/></svg>',
    ],
];
?>
<div class="product-carac">
    <?php foreach ( $columns as $key => $col ) :
        $rows = $groups[ $key ] ?? [];
        if ( empty( $rows ) ) {
            continue;
        }
    ?>
    <div class="product-carac__col">
        <div class="product-carac__col-head">
            <span class="product-carac__col-icon"><?php echo $col['icon']; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
            <h3 class="product-carac__col-title"><?php echo esc_html( $col['title'] ); ?></h3>
        </div>

        <dl class="product-carac__list">
            <?php foreach ( $rows as $attr ) :
                $is_latin   = false !== stripos( $attr['label'], 'latin' );
                $is_shu     = false !== stripos( $attr['label'], 'Force (SHU)' );
                $is_niveau  = $attr['key'] === 'niveau-force';
            ?>
            <div class="product-carac__item">
                <dt class="product-carac__label"><?php echo esc_html( $attr['label'] ); ?></dt>
                <dd class="product-carac__value<?php
                    echo $is_latin  ? ' product-carac__value--latin'  : '';
                    echo $is_shu    ? ' product-carac__value--shu'    : '';
                ?>">
                    <?php if ( $is_niveau ) :
                        $scale = capsi_force_level( $attr['value'] );
                    ?>
                    <span class="product-carac__scale"
                          aria-label="<?php echo esc_attr( $scale['label'] . ', ' . $scale['pips'] . '/5' ); ?>">
                        <?php for ( $i = 1; $i <= 5; $i++ ) :
                            $filled = $i <= $scale['pips'];
                        ?>
                        <span class="product-carac__pip<?php echo $filled ? ' is-filled' : ''; ?>"
                              <?php if ( $filled ) : ?>style="background-color:<?php echo esc_attr( $scale['color'] ); ?>"<?php endif; ?>
                              aria-hidden="true"></span>
                        <?php endfor; ?>
                        <span class="product-carac__scale-label"
                              style="color:<?php echo esc_attr( $scale['color'] ); ?>">
                            <?php echo esc_html( $scale['label'] ); ?>
                        </span>
                    </span>
                    <?php else : ?>
                        <?php echo esc_html( $attr['value'] ); ?>
                    <?php endif; ?>
                </dd>
            </div>
            <?php endforeach; ?>
        </dl>
    </div>
    <?php endforeach; ?>
</div>
