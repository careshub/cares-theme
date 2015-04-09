<?php
/**
 * The Template for displaying all single posts.
 *
 * @package CARES
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		$post_type = get_post_type();

		// If this is a profile page, show the about subnav
		if ( $post_type == 'profile')
			wp_nav_menu( array( 'theme_location' => 'about-subnav', 'menu_class' => 'menu no-bullets horizontal', ) ); 
		?>


		<?php while ( have_posts() ) : the_post(); ?>

			<?php 
			// For custom post types, use their content-custom_post_type.php templates.

			if ( $post_type != 'post') {
				get_template_part( 'content', $post_type );
			} else {
				get_template_part( 'content', 'single' );
				cares_archive_nav( $post_type );
			}
			?>

			<?php 
			if ( ( $post_type != 'profile' ) && ( $post_type != 'portfolio_item' ) ) 
				cares_post_nav(); 
			?>

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