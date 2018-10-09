<?php
//Set up whatever image sizes we need WP to generate on upload. This example uses a lot of them because of quarter-columns through full-width banner images

if ( function_exists( 'add_image_size' ) ) { 
	// Used as the post banner on the front page and single post pages
	add_image_size( 'featured-desktop', 1024, 400, true ); //(cropped)
	// Used for responsive images, eh? See inc/template-tags.php->cares_responsive_thumbnail() for implementation
	add_image_size( 'featured-300', 300, 200, true ); //(cropped)
	add_image_size( 'featured-450', 450, 225, true ); //(cropped)
	add_image_size( 'featured-600', 600, 300, true ); //(cropped)
	add_image_size( 'featured-800', 800, 350, true ); //(cropped)
}

// Add the javascript to the header or enqueue, whichever:
/* <script src="<?php echo get_stylesheet_directory_uri() ?>/js/picturefill.min.js" async></script> */


// Create a helper function to output the new code
/**
 * Prints HTML to create thumbnail compliant with picturefill library.
 */
function cares_responsive_thumbnail( $columns = 1 ) {
	if ( ! $post_id = get_the_ID() )
		return false;
	// Based on http://scottjehl.github.io/picturefill/
	// Info about this image markup method: http://ericportis.com/posts/2014/srcset-sizes/

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