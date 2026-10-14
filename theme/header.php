<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header id="site-header">
  <div class="container">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
      <?php bloginfo( 'name' ); ?>
    </a>
    <nav>
      <?php wp_nav_menu( [ 'theme_location' => 'primary' ] ); ?>
    </nav>
  </div>
</header>
