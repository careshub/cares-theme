<?php
/**
 * The template for displaying Search Results pages.
 *
 * As of now, the search is limited to Projects, so let's leave it at that.
 * @package CARES
 */

get_header(); ?>

	<section id="primary" class="content-area search-results">
		<main id="main" class="site-main content-container" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php printf( __( 'Search Results for: %s', 'cares' ), '<span>' . get_search_query() . '</span>' ); ?>
				</h1>
				
				<?php 
					get_search_form();  
				?>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<div class="half-block">

					<?php get_template_part( 'content', get_post_type() );	?>

				</div>

			<?php endwhile; ?>
			<div>
			<?php cares_paging_nav(); ?>
			</div>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
