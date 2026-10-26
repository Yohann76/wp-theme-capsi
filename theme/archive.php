<?php

use Timber\Timber;

$context                       = Timber::context();
$context['posts']              = Timber::get_posts();
$context['archive_title']      = get_the_archive_title();
$context['archive_description'] = get_the_archive_description();

Timber::render( 'archive.twig', $context );
