<?php
/**
 * @package CARES
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( is_singular( get_post_type() ) ) : ?>
		<header class="entry-header clear">
			<?php if ( has_post_thumbnail() )
				the_post_thumbnail( 'profile-large' );
			?>		
			<div class="entry-header-text">
				<h1 class="entry-title"><?php the_title(); ?></h1>

				<div class="entry-meta">
					<?php if ( function_exists( 'cares_profile_job_title' ) ) : ?>
						<p class="job_title"><?php cares_profile_job_title(); ?></p>
					<?php endif; ?>
					<?php if ( function_exists( 'cares_profile_phone_number' ) ) : ?>
						<?php cares_profile_phone_number(); ?>
					<?php endif; ?>
					<?php if ( function_exists( 'cares_profile_email_address' ) ) : ?>
						<?php cares_profile_email_address(); ?>
					<?php endif; ?>
					<?php if ( function_exists( 'cares_profile_physical_address' ) ) : ?>
						<?php cares_profile_physical_address(); ?>
					<?php endif; ?>
				</div><!-- .entry-meta -->
			</div>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			
	<?php else: // not a single view, provide short form ?>
		<header class="entry-header clear">

			<a href="<?php the_permalink(); ?>" rel="bookmark" class="block">
				<?php if ( has_post_thumbnail() )
					cares_responsive_profile_thumbnail( 3 );
				?>

				<div class="entry-header-text">
					<h3 class="entry-title"><?php the_title(); ?></h3>
					<?php if ( function_exists( 'cares_profile_job_title' ) ) : ?>
						<?php cares_profile_job_title(); ?>
					<?php endif; ?>
				</div><!-- .entry-header-text -->
			</a>

		</header><!-- .entry-header -->

		<!-- <div class="entry-content">
			<?php //the_content(); ?>
			<?php //the_excerpt(); ?>

			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'cares' ),
					'after'  => '</div>',
				) );
			?>
		</div> --><!-- .entry-content -->

		<footer class="entry-footer">

	<?php endif; ?>

	</footer><!-- .entry-footer -->
</article><!-- #post-## -->