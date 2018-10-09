<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package CARES
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function cares_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'cares_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function cares_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'cares_body_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function cares_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}
	
	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'cares' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'cares_wp_title', 10, 2 );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function cares_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'cares_setup_author' );

//well, we're doing this more differently...or trying things, anyway
function cares_limit_front_page_posts( $query ) {
	if( is_front_page() && $query->is_main_query() && !$query->is_paged ) {
        $query->set( 'posts_per_page', 1 );
        //$query->set( 'posts_per_page', 1 );
        //$query->set( 'posts_per_page', 1 );
		/*
		'post_type'      =>  array( 'portfolio_item', 'post' ),
		'posts_per_page' => 1,
		'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'portfolio_item_feature',
					'value' => 'yes'
				),
				array(
					'key' => 'post_feature',
					'value' => 'yes'
				)
			),
		'order' => 'DESC'
		*/
		
		
        if ( $sticky_posts = get_option( 'sticky_posts' ) ) {
        	// get_option( 'sticky_posts' ) returns trashed and draft-status stickies, unhelpfully, so we've got to compare against the post_status, too.
        	$sticky_posts = implode( ',', $sticky_posts );
        	global $wpdb;
        	if ( $sticky_published_posts = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_type LIKE 'post' AND post_status LIKE 'publish' AND ID IN ( {$sticky_posts} )" ) ) {  
		        $query->set( 'post__in', $sticky_published_posts );
		        // Tell WP not to lift a finger on the whole sticky thing, since we just did the heavy lifting.
				$query->set( 'ignore_sticky_posts', 1 );
			}
		}
    } else if ( is_front_page() && $query->is_main_query() && $query->is_paged ) {
        $posts_per_page = isset($query->query_vars['posts_per_page']) ? $query->query_vars['posts_per_page'] : get_option('posts_per_page');
        // If you want to use 'offset', set it to something that passes empty()
        // 0 will not work, but adding 0.1 does (it gets normalized via absint())
        // I use + 1, so it ignores the first post that is already on the front page
        $query->query_vars['offset'] = ( ($query->query_vars['paged'] - 2) * $posts_per_page ) + 1;
    }
}
//add_filter( 'pre_get_posts', 'cares_limit_front_page_posts' );

/**
 * Sets the portfolio page posts to -1.
 *
 * It allows us to go through all posts to find sticky portfolio items,
 * since WP hasn't built up sticky posts option for CPT.
 *
 * @global WP_Query $query WordPress Query object.
 * @return void
 */
function cares_limit_portfolio_page_posts( $query ) {
	if( is_post_type_archive( 'portfolio_item' ) && $query->is_main_query() && !is_admin() ) {
		$query->set( 'posts_per_page', -1 );
		//need to set to -1 so we can see all sticky posts to sort by, regardless of publish date or order
	} 
}
add_filter( 'pre_get_posts', 'cares_limit_portfolio_page_posts' );

/**
 * Orderg by sticky things first on the portfolio item archive, !admin.  
 *
 * Can't use 'sticky_posts' since it is not YET supported for CPT ..  
 * Not sure how this will shake out with ajax, but should be ok if we adjust the queries there..
 *
 * @param array $args Configuration arguments.
 * @return array
 */
add_filter( 'the_posts', 'order_by_sticky', PHP_INT_MAX, 2 );

function order_by_sticky( $posts, $query, $page = 1 ) {
	//define offset so we can reuse this for ajax call? 
	//$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; 

	if ( is_post_type_archive( 'portfolio_item' ) && $query->is_main_query() && $query->is_paged ) {
		// run once
		
		echo 'portfolio item';
		$query->set( 'posts_per_page', -1 );
		
		remove_filter( current_filter(), __FUNCTION__, PHP_INT_MAX, 2 );
		$nonsticky = array();
		$sticky = array();
		$has_yes = false;
		
		foreach ( $posts as $post ) {
			$sticky_meta = get_post_meta( $post->ID, 'portfolio_item_sticky', TRUE );
			if ( $sticky_meta == 'yes' ) {
				echo 'sticky post id: ' . $post->ID;
				$sticky[] = $post;
			} else {
				$nonsticky[] = $post;
			}
		}
		//return all posts, ordered
		$posts = array_merge( $sticky, $nonsticky );
		
		//now, get offset and remove elements from front of array, if necessary
		//array array_splice ( array &$input , int $offset [, int $length [, mixed $replacement = array() ]] )
		$offset = ( $paged - 1 ) * 6;
		$returned_posts = array_splice( $posts, $offset, 6 );
		
		return $returned_posts;
		
	} else if ( is_front_page() && $query->is_main_query() && !is_admin() ) {
		/* do the same for the front page, but:
			- exclude the featured project
			- include post items
		*/
		echo 'hello!';
		// run once (remove this filter here)
		remove_filter( current_filter(), __FUNCTION__, PHP_INT_MAX, 2 );
		$nonsticky = array();
		$sticky = array();
		
		foreach ( $posts as $post ) {
			$sticky_portfolio_meta = get_post_meta( $post->ID, 'portfolio_item_sticky', TRUE );
			$sticky_post_meta = get_post_meta( $post->ID, 'post_sticky', TRUE );
			if ( ( $sticky_portfolio_meta == 'yes' ) || ( $sticky_post_meta == 'yes' ) ) {
				//echo 'yes';
				$sticky[] = $post;
			} else {
				$nonsticky[] = $post;
			}
		}
		//return all posts, ordered
		$posts = array_merge( $sticky, $nonsticky );
		
		//now, get offset and remove elements from front of array, if necessary
		//array array_splice ( array &$input , int $offset [, int $length [, mixed $replacement = array() ]] )
		$offset = ( $paged - 1 ) * 6;
		$returned_posts = array_splice( $posts, $offset, 6 );
		
		return $posts;
	}
	
	//set_query_var( 'paged', 2 );
	
	return $posts;
	//return $returned_posts;
}


function cares_adjust_offset_pagination( $found_posts, $query ) {

    //Define our offset again...
    $offset = 1;

    //Ensure we're modifying the right query object...
    if ( is_front_page() && $query->is_main_query() && $query->is_posts_page ) {
        //Reduce WordPress's found_posts count by the offset... 
        return $found_posts - $offset;
    }
    return $found_posts;
}
//add_filter('found_posts', 'cares_adjust_offset_pagination', 1, 2 );

/* 
 * MISC functions
 */

/* Get 1d array of sticky portfolio ids for post__not_in ajax calls, etc.  */
function cares_get_sticky_portfolio_ids() {

	global $wpdb;
	
	$meta_key 		= 'portfolio_item_sticky';
	$meta_key_value	= 'yes';
	$post_type 		= 'portfolio_item';
	$post_type1 	= 'post';

	$postids = $wpdb->get_col( $wpdb->prepare(
		"
		SELECT      ID
		FROM        $wpdb->posts
		INNER JOIN 	$wpdb->postmeta 
			ON 		$wpdb->posts.id = $wpdb->postmeta.post_id
		WHERE       ( $wpdb->posts.post_type = %s
					OR $wpdb->posts.post_type = %s)
			AND 	$wpdb->postmeta.meta_key = %s 
			AND 	$wpdb->postmeta.meta_value = %s
		LIMIT %d
		",
		$post_type,
		$post_type1,
		$meta_key, 
		$meta_key_value,
		10000
	) ); 
	
	return $postids;
}

/*
 * Get most-recently published Featured Item
 *
 * Return portfolio_item or post with appropriate 'featured' designation in meta data and 
 * most recently published, if no 'featured', return most recent portfolio_item or post
 *
 * @return post
 */
 function cares_get_featured_item() {
 
	global $wpdb;
	
	$featured_item = new WP_Query(
		array(
			'post_type'      =>  array( 'portfolio_item', 'post' ),
			'posts_per_page' => 1,
			'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => 'portfolio_item_feature',
						'value' => 'yes'
					),
					array(
						'key' => 'post_feature',
						'value' => 'yes'
					)
				),
			'order' => 'DESC'
		)
	); 

	//if there are no featured posts, feature the latest post or portfolio item
	if ( !( $featured_item->have_posts() ) ) {

		$featured_item = new WP_Query(
			array(
				'post_type'      =>  array( 'portfolio_item', 'post' ),
				'posts_per_page' => 1,
				'order' => 'DESC'
			)
		);
		
	}
	
	return $featured_item;
 
}

/* 
 * Interrupt front-end searches to only look through projects right now, as requested..
 *
 */ 
add_action('pre_get_posts','cares_search_posts');
function cares_search_posts( $query ) { 

	if ( !is_admin() && $query->is_main_query() ) {
		if ($query->is_search) {
			$query->set('post_type', 'portfolio_item');
		}
	}
}

/* 
 * Return related project object array
 *
 */
function cares_get_related( $post_type = 'portfolio_item' ){

	global $wpdb;


}

/* 
 * Return number of posts
 *
 * @param string $post_type Post Type
 * @return int $total_posts Total number of posts of type
 *
 */
function cares_get_total_posts_of_type( $post_type = 'portfolio_item' ){ //portfolio_item == project

	global $wpdb;
	
	$args = array( 
		'post_type' 		=> $post_type,
		'posts_per_page'	=> -1,
		'post_status'		=> 'publish'
	);
	
	$post_query = new WP_Query( $args );
	
	return $post_query->found_posts;

}

