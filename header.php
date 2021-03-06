<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package CARES
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link href='//fonts.googleapis.com/css?family=Merriweather+Sans:300,400,300italic' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" type="image/x-icon" href="<?php bloginfo('template_url'); ?>/img/favicon.ico"/>

	<!--<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700|Lato:400,700,400italic|Open+Sans:400,700,400italic|Source+Sans+Pro:400,600,700,400italic|Arimo:400,400italic,700' rel='stylesheet' type='text/css'>-->
	<link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,400italic' rel='stylesheet' type='text/css'>


	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

	<header id="masthead" class="site-header" role="banner">
		<div class="site-header-inner">
			<div class="site-branding">
				<span><a title="MU Homepage" id="mu-logo" class="mu-logo" href="http://www.missouri.edu/"></a>

				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class=""><span class="screen-reader-text"><?php bloginfo( 'name' ); ?></span></a></h1>
				</span>
				<!-- <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2> -->

			</div>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<button class="menu-toggle"><?php _e( 'Primary Menu', 'cares' ); ?></button>
				<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'cares' ); ?></a>

				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			</nav><!-- #site-navigation -->
		</div><!-- .site-header-inner -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
