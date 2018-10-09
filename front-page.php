<?php
/**
 * Template Name: Front Page
 *
 * Used to build the front-facing page.
 *
 * @package CARES
 */

get_header(); 

//Get Home Page content
while ( have_posts() ) : the_post();
	$front_page_content = get_the_content();
endwhile;

wp_reset_postdata();

/*	The second part of the page show Recent Projects
 * 
 * We can't use pre_get_posts for a page template, 
 * 	per http://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts
 *		- First, show 'sticky' posts AND projects
 *		- Next, show recent posts
 *		- limited to 6 total for now and then ajax/infinite more as necessary
 *
 */
 
//the total number of Recent Projects to display
$total_num_projects = cares_get_total_posts_of_type( cares_get_portfolio_cpt_name() );
$num_recent_projects = 6;

$sticky_postids = cares_get_sticky_portfolio_ids();

//set flag for first part of Recent Projects loop
if ( !( is_null ( $sticky_postids ) ) ) $have_sticky_posts = true;

$num_recent = $num_recent_projects - sizeof( $sticky_postids );

$recent_proj_loop = new WP_Query(
	array(
		'post_type'      	=> cares_get_portfolio_cpt_name(),
		'posts_per_page' 	=> $num_recent,
		'order' 			=> 'DESC', 
		'post__not_in'		=> $sticky_postids
	)
); 



// The first part of the page is the 'featured post' item
$featured_item_loop = cares_get_featured_item();

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( $featured_item_loop->have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while( $featured_item_loop->have_posts() ) : $featured_item_loop->the_post(); ?>
				<div class="featured">
				<?php
					get_template_part( 'content', get_post_format() );
				?>
				</div>
			<?php endwhile; ?>

			<?php cares_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif;
		wp_reset_postdata();
		?>
		
		
		<?php // Next, loop through the three most recent portfolio items, if this is the front, front page 
		//account for sticky projects
		
		if ( ! is_paged() ):
		?>
		<hr>
		<div class="cares-lead">
			<h3>The Center for Applied Research and Engagement Systems (CARES) is an award-winning mapping and data visualization center at the University of Missouri.  
			CARES integrates technology and information to support decision making processes.  We're passionate about our partnerships and our projects - take a look:</h3>
		</div>
			<?php if ( $have_sticky_posts ) : ?>
				<!--<hr />-->
				<section id="related-projects" class="content-container">
					<!--<h2 class="section-title">Recent Projects</h2>-->

				<?php foreach( $sticky_postids as $id ) { 
					//set up post data for sticky posts
					$post = get_post( $id ); 
					setup_postdata( $post ); ?>
					
					<div class="third-block">
						<?php get_template_part( 'content', get_post_type( $post ) ); ?>
					</div>

				<?php } ?>
				
				<input type="hidden" name="project_page" value="1" autocomplete="off" />

				<?php //cares_archive_nav( 'portfolio_item', 'See More Projects' ); ?>
			<?php endif; ?>
			
			<?php if ( $recent_proj_loop->have_posts() ) : 
				
				// if we don't have any sticky posts, we'll need to render the <section> and section-title
				if ( !$have_sticky_posts ) { ?>
					<hr />
					<section id="related-projects" class="content-container">
					
						 <h2 class="section-title">Recent Projects</h2>
						 
				<?php } ?>
				
				<?php while( $recent_proj_loop->have_posts() ) : $recent_proj_loop->the_post(); ?>

					<div class="third-block">
						<?php get_template_part( 'content', get_post_type() ); ?>
					</div>

				<?php endwhile; ?>
				
				<?php if ( !$have_sticky_posts ) { 
					// if we don't have any sticky posts, we'll need to render the hidden page number here ?>
					<input type="hidden" name="project_page" value="1" />
				<?php } ?>
				</section>
			<?php endif; ?>
	
		<?php endif; // Check for is-paged() ?>
	
		<?php if ( $total_num_projects > 6 ){ ?>
			<div class="loadmore aligncenter">
				<a class="more-projects">See More Projects <!--&#x25BC;--></a>
				<div class="spinny"></div>
				<div id="more_posts"><input type="hidden" id="num_more_posts" value="<?php echo '1'; ?>" /></div>
			</div>
		<?php } ?>
		
		<?php if ( !empty( $front_page_content ) ) { ?>
			<div class="clear"></div>
			<hr />
			
			<div class="lower-content">
							
				<?php echo $front_page_content; ?>
			</div>
		<?php } ?>
		
		</main><!-- #main -->
	</div><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
