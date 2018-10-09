
<?php
/**
 * Template Name: Post Archive
 * 
 * We've altered the loop here, since it's main, to order by sticky posts (and we can't user pre_get_posts on a page...grr)
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package CARES
 */

get_header(); 


?>

	<section id="primary" class="content-area post-archive">
		<main id="main" class="site-main content-container" role="main">
		
		<?php //loop it
		if ( have_posts() && !( isset( $searchkey ) ) ) : ?>

			<header class="page-header">
			
				<h1 class="page-title">
					Posts
				</h1>
				<?php 
					get_search_form();  
				?>
				
				<?php
				// Show an optional term description.
				$term_description = term_description();
				if ( ! empty( $term_description ) ) :
					printf( '<div class="taxonomy-description">%s</div>', $term_description );
				endif;
				
				
				?>
				
			</header><!-- .page-header -->

			<section id="recent-projects">
			<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
				<div class="half-block">

					<?php get_template_part( 'content', get_post_type() ); ?>

				</div>
				<?php endwhile; ?>
			
			</section>
			
			
			<input type="hidden" name="post_page" value="1" />
			
			<div class="loadmore aligncenter">
				<a class="more-projects">See More Posts</a>
				<div class="spinny"></div>
				<div id="more_posts"><input type="hidden" id="num_more_posts" value="<?php if ( $total_num_projects > 6 ) echo '1'; else echo '0'; ?>" /></div>
			</div>
			
		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
