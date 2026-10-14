<?php

use Timber\Timber;

$context = Timber::context();

ob_start();
woocommerce_content();
$context['woocommerce_content'] = ob_get_clean();

Timber::render( 'woocommerce.twig', $context );
