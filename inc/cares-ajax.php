<?php
/*
 *	File for ajax functions of theme front-end
 *		- Load More Project on front page
 */

add_action( 'wp_ajax_load_more_projects', 'cares_ajax_load_more_projects' );
add_action( 'wp_ajax_nopriv_load_more_projects', 'cares_ajax_load_more_projects' ); //non loggedin users

/*
 * AJAX function for returning Related projects
 *
 * @params
 * @return array $output Array of more projects to be displayed.
 *
 */
function cares_ajax_load_more_projects() {

	/* Uses page number of recent posts on homepage to return bundled html (?is this best?) of [6] more projects
	 * - must account for sticky posts that are in loop - 4 scenarios (of two types):
	 *		A. If we have more sticky posts than are currently displayed on the page
	 *			1. We have enough sticky posts to fill the next (returned) page, too
	 *			2. We have some more sticky posts, but need to fill in the rest of the returned w/ normal
	 *		B. If we have no more sticky posts
	 *			3. We have just enough to fill the current page (page FROM) but need to return all as normal
	 *			4. We have already have some normal on the page, so will need to offset the returned normal posts
	 */

	global $wpdb;

	//check_ajax_referer('cares_ajax_data', 'cares_ajax_data_nonce');

	//get current page number and posts_per_page, if they exist
	if ( !isset ( $_GET['current_page_num'] ) ) return;
	$current_page_num = intval( $_GET['current_page_num'] );
	$next_page_num = $current_page_num + 1;
	$more_posts = 0; //var to tell returned ajax if there are more posts after we return $posts_per_page

	//how many more posts to display?
	$posts_per_page = ( isset( $_GET['posts_per_page'] ) ) ? $_GET['posts_per_page'] : 6;
	$post_type = cares_get_portfolio_cpt_name();

	//need to take into account the prior projects loaded (sticky ones)..
	//get 1d array of ids of all sticky projects
	$total_sticky_ids = cares_get_sticky_portfolio_ids();
	$remaining_sticky_ids = $total_sticky_ids; //because array splice is destructive

	//account for Featured Post on home page, if is project
	if ( isset ( $_GET['is_front_page'] ) ){
		$is_front_page = true; //for later?
	}

//TODO: this.  :P

	//get total number of projects
	$total_num_projects = cares_get_total_posts_of_type( cares_get_portfolio_cpt_name() );
	$how_many_currently_displayed = $posts_per_page * $current_page_num;

	//echo 'Total Num Projects: ' . $total_num_projects; //this is correct

	if ( ( $total_num_projects - $how_many_currently_displayed ) > 6 ) {
		$more_posts = 1;
	}

	$output = '';
	if ( sizeof( $total_sticky_ids ) > ( $posts_per_page * $current_page_num ) ) {
		// We have more sticky portfolio items than slots on the current page = more to display!

		if ( sizeof( $total_sticky_ids) > ( $posts_per_page * $next_page_num ) ) {
			// there are enough sticky posts in total to fill this page, too.  All is sticky posts.

			//get number of sticky posts already displayed to remove from $total_sticky_ids
			$how_many_to_remove = $posts_per_page * $current_page_num;

			//array array_splice ( array &$input , int $offset [, int $length [, mixed $replacement = array() ]] )
			array_splice ( $remaining_sticky_ids, 0, $how_many_to_remove ) ; //returns end of $total_sticky_ids

			$count = 0; //should we do a regular for loop?
			foreach( $remaining_sticky_ids as $id ) {

				//set up post data for sticky posts
				$post = get_post( $id );

				//get template part for each (we insert display-specific markup in javascript)
				$output .= get_template_part( 'content', get_post_type( $post ) );
				$count++;

				//if we have our 6 (0-indexed) posts, get out
				if ( $count == 6 )
					break;

			}

		} else {
			//we display SOME sticky posts but need to pull in the rest as normal

			//how many more stickys exist if we subtract the num currently displayed?
			$how_many_more_sticky = sizeof( $total_sticky_ids ) - ( $posts_per_page * $current_page_num ) ; // 1-6

			//cull sticky_ids down to remaining $how_many_more_sticky (array offset)
			$how_many_to_remove = sizeof( $total_sticky_ids ) - $how_many_more_sticky;

			//array array_splice ( array &$input , int $offset [, int $length [, mixed $replacement = array() ]] )
			array_splice ( $remaining_sticky_ids, 0, $how_many_to_remove ) ; //returns end of $total_sticky_ids

			//now, get remaining posts from non-sticky pool
			$how_many_non_sticky = $posts_per_page - $how_many_more_sticky;

			//no offset needed, since we are only starting to pull non-sticky projects here
			$more_proj_loop = new WP_Query(
				array(
					'post_type'      	=> $post_type,
					'posts_per_page' 	=> $how_many_non_sticky,
					'post__not_in' 		=> $total_sticky_ids
				)
			);

			//Now, fill output buffer
			// First, get remaining sticky post content
			foreach( $remaining_sticky_ids as $id ) {
				//set up post data for sticky posts
				$post = get_post( $id );

				//get template part for each (we insert display-specific markup in javascript)
				$output .= get_template_part( 'content', get_post_type( $post ) );
			}

			//Next, get non-sticky post content
			while( $more_proj_loop->have_posts() ) : $more_proj_loop->the_post();

				//fill output array with template part for each (we insert display-specific markup in javascript)
				$output .= get_template_part( 'content', get_post_type() );

			endwhile;
		}

	} else {

		if ( sizeof( $total_sticky_ids ) == ( $posts_per_page * $current_page_num ) ) {
		// We have displayed all sticky portfolio items and need to move on with our lives
			$offset = 0; //we get all the non-stickies we have

		} else {  // sizeof ( $total_sticky_ids) < ( $posts_per_page * $current_page_num )
		// no more sticky posts to display, but we need to offset the normal query with already-displayed normal posts
			// account for posts_per_page - sizeof ( sticky_ids )
			$offset = ( $posts_per_page * $current_page_num ) - sizeof( $total_sticky_ids ) + 1;
			//echo ( $posts_per_page * $current_page_num ) . "  ( $posts_per_page * $current_page_num ) ";
			//echo 'offset: ' . $offset;
		}

		//Now, fetch more projects
		$more_proj_loop = new WP_Query(
			array(
				'post_type'      	=> $post_type,
				'posts_per_page' 	=> $posts_per_page,
				'post__not_in' 		=> $total_sticky_ids,
				'offset' 			=> $offset
			)
		);

		while( $more_proj_loop->have_posts() ) : $more_proj_loop->the_post();

			//fill output array with template part for each (we insert display-specific markup in javascript)
			$output .= get_template_part( 'content', get_post_type() );

		endwhile;

	}

	echo $output; //I don't even think we need this!
	echo '<div id="more_posts"><input type="text" id="num_more_posts" value="' . $more_posts . '" /></div>';
	die();

}