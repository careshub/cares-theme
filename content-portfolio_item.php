<?php
/**
 * The template part for displaying portfolio items.
 *
 * @package CARES
 */


//are there any Related Projects to this Project?
//echo 'TOP' . "\n";
//echo 'already pulled related: ' . $done_w_related_projects . "\n";
//echo 'allowed related projects: ' . $allow_related_projects . "\n";

if ( isset( $allow_related_projects ) && isset ( $in_related_projects_loop ) ){
	//only go 1-deep to avoid infinite recursion
	unset( $allow_related_projects );
	unset( $related_projects );
	//echo 'unsetting';
	
} else if ( !isset ( $in_related_projects_loop ) ) {

	//$top_level_project = true;
	$allow_related_projects = true;
	//echo "\n" . 'Setting related projects';
	$related_projects = get_post_meta( get_the_ID(), 'related_projects', false );
	
	if ( !( empty( $related_projects ) ) ) {
		$related_projects = array_values( $related_projects[0] );

		//setup wp_query args for Related Projects
		$related_args = array(
			'post_type' 		=> 'portfolio_item',
			'post__in' 			=> $related_projects
		);
		
		//run the query
		//Not yet, causing recursion - new method/template file needed..
		$related_project_query = new WP_Query( $related_args );

		//echo sizeof( $related_project_query );
	}
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( is_singular( get_post_type() ) && !( isset( $in_related_projects_loop ) ) ) : ?>
		<header class="entry-header single">
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
			
			<?php if ( function_exists( 'cares_project_client' ) ) : ?>
				<div class="entry-meta">
					<p>
						<?php if ( cares_project_client() != '' ){ 
							$client = cares_project_client(  ); ?>
							<!--<strong>Client: <?php //echo $client; ?></strong>-->
							<strong>Sponsor: <?php echo $client; ?></strong>
							<!-- .entry-meta -->
						<?php } ?>
						<!--<strong>Client: <?php cares_project_client(); ?></strong>-->
						<?php if ( function_exists( 'cares_project_url' ) ) { 
							if ( cares_project_url() != '' ){ ?>
								<br />
								Project URL: <a href="<?php echo cares_project_url(); ?>"><?php echo cares_project_url(); ?></a>
								<!-- .entry-meta -->
							<?php } ?>
						<?php } ?>
					</p>
					
				</div><!-- .entry-meta -->
			<?php endif; ?>
			
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

				/*printf(
					$meta_text,
					$category_list,
					$tag_list,
					get_permalink()
				);*/
			?>

			<?php edit_post_link( __( 'Edit', 'cares' ), '<span class="edit-link">', '</span>' ); ?>
		</footer>
		
		<?php cares_post_nav(); 
		
		//display Related Projects if there are any
		
		
		if ( isset ( $related_project_query ) && ( $related_project_query->have_posts() ) && isset( $allow_related_projects ) ) : ?>
		
		<?php $in_related_projects_loop = true; ?>
			<hr />
			
			<section id="related-projects" class="content-container">
				<h2 class="section-title">Related Projects</h2>

				<?php while( ( $related_project_query->have_posts() ) ) : $related_project_query->the_post(); ?>

					<div class="third-block">
						<?php //get_template_part( 'content', get_post_type() ); ?>
						
						<?php //include template part in a way that we can pass vars (so, avoid recursion)
						include( locate_template( 'content-portfolio_item.php' ) ); 
						
						?>
					</div>
					
				<?php endwhile; ?>
					
			</section>
			<div class="clear"></div>
		
		
		<?php endif; ?>

	<?php else: // not a single view, provide short form ?>

		<header class="entry-header">
			<div class="block">
				<a href="<?php the_permalink(); ?>" rel="bookmark">
					<?php if ( has_post_thumbnail() )
						cares_responsive_thumbnail( 2 );
					?>
					<h3 class="entry-title"><?php the_title(); ?></h3>
				</a>
				
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
				
				
				<?php if ( function_exists( 'cares_project_client' ) ) : ?>
					<div class="entry-meta">
						<?php if ( cares_project_client() != '' ){ 
							$client = cares_project_client(  ); ?>
							<!--<p><em>Client: <?php //echo $client; ?></em></p>-->
							<p><em>Sponsor: <?php echo $client; ?></em></p>
							<!-- .entry-meta -->
						<?php } ?>
						<!--<p><em>Client: <?php cares_project_client(); ?></em></p>-->
						<?php //echo "<p><b>Sticky? " . get_post_meta( $post->ID, 'portfolio_item_sticky', TRUE ) . "</b></p>"; ?>
					</div><!-- .entry-meta -->
				<?php endif; ?>
			
			</div>
			

			<!-- <div class="entry-meta"> -->
				<?php //cares_posted_on(); ?>
			<!-- </div> --><!-- .entry-meta -->
		</header><!-- .entry-header -->

		

		<footer class="entry-footer">
			
	<?php endif; ?>

	</footer><!-- .entry-footer -->
</article><!-- #post-## -->