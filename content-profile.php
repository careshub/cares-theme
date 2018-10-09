<?php
/**
 * @package CARES
 */
?>

<?php
	//are there any Related Projects to this Profile?
	$related_projects = get_post_meta( get_the_ID(), 'related_projects', false );
	
	//print_r ($related_projects[0]);
	//echo sizeof ($related_projects[0]);
	
	//nope, this hates post__in, too
	//if ( isset( $related_projects ) ) $related_projects_list = implode(',', $related_projects[0]);
	//echo $related_projects_list; // this echoes: 12,4
	
	//$related_projects_list = array ( 1, 4, 12, 4);

	if ( !( empty( $related_projects ) ) ) {
		$related_projects = array_values( $related_projects[0] );
		//print_r( $related_projects);
		//setup wp_query args for Related Projects
		$related_args = array(
			'post_type' 		=> cares_get_portfolio_cpt_name(),
			'post__in' 			=> $related_projects
		);
		
		//run the query
		$related_project_query = new WP_Query( $related_args );
		//echo $related_project_query->found_posts;
	}




?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( is_singular( get_post_type() ) ) : ?>
		<header class="entry-header clear">
			<?php if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'profile-large' );
			} else { ?>
				<img class="attachment-profile-large wp-post-image" width="300" height="300" alt="profile image" src="<?php echo CARES_PROFILE_IMG_DEFAULT; ?>">
			<?php }
			?>		
			<div class="entry-header-text">
				<h1 class="entry-title"><?php the_title(); ?></h1>

				<div class="entry-meta">
					<?php if ( function_exists( 'cares_profile_job_title' ) ) : ?>
						<h2 class="job_title"><?php cares_profile_job_title(); ?></h2>
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
		
		<?php if ( isset ($related_project_query) && ( $related_project_query->have_posts() ) ) : ?>
			<hr />
			<?php //echo $related_project_query->found_posts; ?>
			<section id="related-projects" class="content-container">
				<h2 class="section-title"><?php the_title(); ?>'s Highlighted Projects</h2>

				<?php while( $related_project_query->have_posts() ) : $related_project_query->the_post(); ?>

					<div class="third-block">
						<?php get_template_part( 'content', get_post_type() ); ?>
					</div>

				<?php endwhile; ?>
					
			</section>
			<div class="clear"></div>
			
		<?php endif; ?>
			
	<?php else: // not a single view, provide short form ?>
		<header class="entry-header clear">

			<a href="<?php the_permalink(); ?>" rel="bookmark" class="block">
				<?php if ( has_post_thumbnail() ){
					cares_responsive_profile_thumbnail( 3 );
				} else { ?>
					<img class="attachment-profile-large wp-post-image" width="300" height="300" alt="profile image" src="<?php echo CARES_PROFILE_IMG_DEFAULT; ?>">
				<?php }
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