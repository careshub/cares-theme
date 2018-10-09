<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package CARES
 */

if ( ! function_exists( 'cares_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function cares_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'cares' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'cares' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'cares' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'cares_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @since Twenty Fourteen 1.0
 */
function cares_post_nav( ) {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}

	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'cares' ); ?></h1>
		<div class="nav-links">
			<?php
			$post_type = get_post_type();
			if ( $post_type == cares_get_portfolio_cpt_name() ) {
				$prev_link_text = '<span class="meta-nav">Previous Project</span>%title';
				$next_link_text = '<span class="meta-nav">Next Project</span>%title';
			} else {
				$prev_link_text = '<span class="meta-nav">Previous Post</span>%title';
				$next_link_text = '<span class="meta-nav">Next Post</span>%title';
			}
			
			if ( is_attachment() ) :
				previous_post_link( '%link', __( '<span class="meta-nav">Published In</span>%title', 'cares' ) );
			else :
				previous_post_link( '%link', __( $prev_link_text, 'cares' ) );
				next_post_link( '%link', __( $next_link_text, 'cares' ) );
			endif;
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'cares_archive_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @since Twenty Fourteen 1.0
 */
function cares_archive_nav( $post_type, $link_text = null ) {

	// Get the name of the post type
	$obj = get_post_type_object( $post_type );
	if ( ! isset( $obj ) ) return;
	$archive_name = $obj->labels->name;
	
	//if no nav text specified, set default based on CPT name
	if ( ! isset( $link_text ) )
	$link_text = "See all " . $archive_name;
	
	?>
	<nav class="navigation to-archive-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Archive navigation', 'cares' ); ?></h1>
		<div class="nav-links">
			<?php $archive_link = get_post_type_archive_link( $post_type ); ?>
			<a href="<?php echo $archive_link; ?>" alt="<?php echo $link_text; ?>"><h3><?php echo $link_text; ?></h3></a>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'cares_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function cares_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	printf( __( '<span class="posted-on">Posted on %1$s</span><span class="byline"> by %2$s</span>', 'cares' ),
		sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_permalink() ),
			$time_string
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		)
	);
}
endif;
if ( ! function_exists( 'cares_responsive_thumbnail' ) ) :
/**
 * Prints HTML to create thumbnail compliant with picturefill library.
 */
function cares_responsive_thumbnail( $columns = 1 ) {
	if ( ! $post_id = get_the_ID() )
		return false;

	// Breakpoints in my theme:
	// 1 columns: max 1024px wide
	// 2 columns: default one col, max 544px wide; 37em to two, max 442px wide
	// 3 columns: default one col, max 735px wide; 50em to three, max 289px wide
	// 4 columns: default one col, max 544px wide; 37em to two, max 442px wide; 64em to four, 206px wide
	// "srcset" will always be the same, since it simply tells the browser what options are available.
	// "sizes" will change based on number of columns, that's where we warn the browser about our breakpoints
	// the unit "vw" is basically equal to % viewport width. Plus, who doesn't like VWs?

	// Get the thumbnail image urls and alt data
	$thumb_id 		= get_post_thumbnail_id( $post_id );
	$xl_image_url 	= wp_get_attachment_image_src( $thumb_id, 'featured-desktop');
	$l_image_url 	= wp_get_attachment_image_src( $thumb_id, 'featured-800');
	$m_image_url 	= wp_get_attachment_image_src( $thumb_id, 'featured-600');
	$s_image_url 	= wp_get_attachment_image_src( $thumb_id, 'featured-450');
	$xs_image_url 	= wp_get_attachment_image_src( $thumb_id, 'featured-300');

	$alt_text = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );
	// Lazy people leave the alt data empty, so we'll fill it in with the post title, if necessary
	$alt_text = $alt_text ? $alt_text : get_the_title( $post_id );

	switch ( $columns ) {
		case '4':
			$breakpoints = '(min-width: 37em) 50vw,
							(min-width: 64em) 25vw,
							100vw';
			break;
		case '3':
			$breakpoints = '(min-width: 50em) 33.3vw, 
							100vw';
			break;
		case '2':
			$breakpoints = '(min-width: 37em) 50vw, 
							100vw';
			break;
		case '1':
		default:
			$breakpoints = '100vw';
			break;
	}


	?>
	<img src="<?php echo $xs_image_url[0]; ?>"
     srcset="<?php echo $xl_image_url[0]; ?> 1024w,
             <?php echo $l_image_url[0]; ?> 800w,
             <?php echo $m_image_url[0]; ?> 600w,
             <?php echo $s_image_url[0]; ?> 450w,
             <?php echo $xs_image_url[0]; ?> 300w"
     sizes="<?php echo $breakpoints; ?>"
     alt="<?php echo $alt_text; ?>" />
     <?php
}
endif;


/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function cares_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'cares_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'cares_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so cares_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so cares_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in cares_categorized_blog.
 */
function cares_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'cares_categories' );
}
add_action( 'edit_category', 'cares_category_transient_flusher' );
add_action( 'save_post',     'cares_category_transient_flusher' );
