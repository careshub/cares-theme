<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package CARES
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( is_singular( get_post_type() ) ) : ?>
	
		<header class="entry-header">
			<?php if ( has_post_thumbnail() )
					cares_responsive_thumbnail();
				?>
			<div class="entry-header-text">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</div>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'cares' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
		
	<?php else: // not a single view, provide short form ?>

		<header class="entry-header">

			<a href="<?php the_permalink(); ?>" rel="bookmark" class="block">
				<?php if ( has_post_thumbnail() )
					cares_responsive_thumbnail( 2 );
				?>
				<h3 class="entry-title"><?php the_title(); ?></h3>
			</a>
			
		</header><!-- .entry-header -->

		<div class="entry-content">
		
			<?php the_excerpt(); ?>
			
		</div><!-- .entry-content -->
			
	<?php endif; ?>
		
</article><!-- #post-## -->
