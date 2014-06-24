<?php
/**
 * Template Name: About Section
 *
 * Description: Used to display "About" pages and subpages.
 *
 * @package CARES
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php wp_nav_menu( array( 'theme_location' => 'about-subnav', 'menu_class' => 'menu no-bullets horizontal', ) ); ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					// if ( comments_open() || '0' != get_comments_number() ) :
					// 	comments_template();
					// endif;
				?>

			<?php endwhile; // end of the loop. ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
