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

function cares_limit_front_page_posts( $query ) {
	if( is_front_page() && $query->is_main_query() && !$query->is_paged ) {   	
        $query->set( 'posts_per_page', 1 );
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
add_filter( 'pre_get_posts', 'cares_limit_front_page_posts' );

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
add_filter('found_posts', 'cares_adjust_offset_pagination', 1, 2 );
