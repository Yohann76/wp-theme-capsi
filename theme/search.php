<?php

use Timber\Timber;

$context                 = Timber::context();
$context['posts']        = Timber::get_posts();
$context['search_query'] = get_search_query();

Timber::render( 'search.twig', $context );
