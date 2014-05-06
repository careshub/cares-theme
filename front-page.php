<?php
/**
 * The front page template file.
 *
 * Used to build the front-facing page.
 *
 * @package CARES
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					the_post_thumbnail('featured-desktop');
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>

			<?php cares_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		<?php // Next, loop through the three most recent portfolio items ?>

		<?php $loop = new WP_Query(
				array(
					'post_type'      => 'portfolio_item',
					'posts_per_page' => 3,
				)
			); ?>

			<?php if ( $loop->have_posts() ) : ?>
				<div class="content-container">

				<?php while( $loop->have_posts() ) : $loop->the_post(); ?>

					<div class="third-block">
						<?php get_template_part( 'content', get_post_type() ); ?>
					</div>

				<?php endwhile; ?>

				</div>

			<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
