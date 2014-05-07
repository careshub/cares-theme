<?php
/**
 * @package CARES
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( is_singular( get_post_type() ) ) : ?>
		<header class="entry-header">
			<?php if ( has_post_thumbnail() )
				the_post_thumbnail( 'featured-desktop' );
			?>		
			<div class="entry-header-text">
				<h1 class="entry-title"><?php the_title(); ?></h1>
				
				<div class="entry-meta">
					<?php cares_posted_on(); ?>
				</div><!-- .entry-meta -->
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

		<footer class="entry-footer">
			<?php
				/* translators: used between list items, there is a space after the comma */
				$category_list = get_the_category_list( __( ', ', 'cares' ) );

				/* translators: used between list items, there is a space after the comma */
				$tag_list = get_the_tag_list( '', __( ', ', 'cares' ) );

				if ( ! cares_categorized_blog() ) {
					// This blog only has 1 category so we just need to worry about tags in the meta text
					if ( '' != $tag_list ) {
						$meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'cares' );
					} else {
						$meta_text = __( 'Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'cares' );
					}

				} else {
					// But this blog has loads of categories so we should probably display them here
					if ( '' != $tag_list ) {
						$meta_text = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'cares' );
					} else {
						$meta_text = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'cares' );
					}

				} // end check for categories on this blog

				printf(
					$meta_text,
					$category_list,
					$tag_list,
					get_permalink()
				);
			?>

			<?php edit_post_link( __( 'Edit', 'cares' ), '<span class="edit-link">', '</span>' ); ?>

	<?php else: // not a single view, provide short form ?>

		<header class="entry-header">

			<a href="<?php the_permalink(); ?>" rel="bookmark" class="block">
				<?php if ( has_post_thumbnail() )
					cares_responsive_thumbnail( 3 );
				?>
				<h3 class="entry-title"><?php the_title(); ?></h3>

				<?php if ( function_exists( 'cares_project_client' ) ) : ?>
					<div class="entry-meta">
						Client: <?php cares_project_client(); ?>
					</div><!-- .entry-meta -->
				<?php endif; ?>
			</a>

			<!-- <div class="entry-meta"> -->
				<?php //cares_posted_on(); ?>
			<!-- </div> --><!-- .entry-meta -->
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php //the_content(); ?>
			<?php the_excerpt(); ?>

			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'cares' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php
				/* translators: used between list items, there is a space after the comma */
				$category_list = get_the_category_list( __( ', ', 'cares' ) );

				/* translators: used between list items, there is a space after the comma */
				$tag_list = get_the_tag_list( '', __( ', ', 'cares' ) );

				if ( ! cares_categorized_blog() ) {
					// This blog only has 1 category so we just need to worry about tags in the meta text
					if ( '' != $tag_list ) {
						$meta_text = __( 'This entry was tagged %2$s.', 'cares' );
					} else {
						$meta_text = __( '', 'cares' );
					}

				} else {
					// But this blog has loads of categories so we should probably display them here
					if ( '' != $tag_list ) {
						$meta_text = __( 'This entry was posted in %1$s and tagged %2$s.', 'cares' );
					} else {
						$meta_text = __( 'This entry was posted in %1$s.', 'cares' );
					}

				} // end check for categories on this blog

				printf(
					$meta_text,
					$category_list,
					$tag_list,
					get_permalink()
				);
			?>

			<?php //edit_post_link( __( 'Edit', 'cares' ), '<span class="edit-link">', '</span>' ); ?>


	<?php endif; ?>

	</footer><!-- .entry-footer -->
</article><!-- #post-## -->