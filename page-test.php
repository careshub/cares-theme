<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package CARES
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

<?php
$post_id = 32;
$thumb_id 		= get_post_thumbnail_id( $post_id );
$xl_image_url 	= wp_get_attachment_image_src( $thumb_id, 'featured-desktop');
$l_image_url 	= wp_get_attachment_image_src( $thumb_id, 'featured-800');
$m_image_url 	= wp_get_attachment_image_src( $thumb_id, 'featured-600');
$s_image_url 	= wp_get_attachment_image_src( $thumb_id, 'featured-450');
$xs_image_url 	= wp_get_attachment_image_src( $thumb_id, 'featured-300');
var_dump($thumb_id);
var_dump( wp_get_attachment_image_src( 525, 'featured-300') );

// var_dump(expression);

echo get_the_post_thumbnail( $post_id, 'featured-300' );

var_dump( get_intermediate_image_sizes() );

if( extension_loaded('gd') ) {
    print_r(gd_info());
} else {
    echo 'GD is not available.';
}

?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
