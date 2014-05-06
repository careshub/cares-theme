<?php
/**
 * The Template for displaying all single posts.
 *
 * @package CARES
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php 
			// For custom post types, use their content-custom_post_type.php templates.
			$post_type = get_post_type();
			if ( $post_type != 'post') {
				get_template_part( 'content', $post_type );
			} else {
				get_template_part( 'content', 'single' );
			}
			?>

			<?php twentyfourteen_post_nav(); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() ) :
					comments_template();
				endif;
			?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>