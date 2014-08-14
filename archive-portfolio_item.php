
<?php
/**
 * The template for displaying Portfolio Archive pages.
 * 
 * We've altered the loop here, since it's main, to order by sticky posts
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package CARES
 */

get_header(); 

//the total number of published projects that we have
$total_num_projects = cares_get_total_posts_of_type( 'portfolio_item' );

?>

	<section id="primary" class="content-area portfolio-archive">
		<main id="main" class="site-main content-container" role="main">
		
		<?php //loop it
		if ( have_posts() && !( isset( $searchkey ) ) ) : ?>

			<header class="page-header">
			
				<h1 class="page-title">
					Projects
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
			
			
			<input type="hidden" name="project_page" value="1" />
			<?php if ( $total_num_projects > 6 ){ ?>
				<div class="loadmore aligncenter">
					<a class="more-projects">See More Projects</a>
					<div class="spinny"></div>
					<div id="more_posts"><input type="hidden" id="num_more_posts" value="<?php  echo '1'; ?>" /></div>
				</div>
			<?php } ?>
			
		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
