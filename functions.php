<?php
/**
 * CARES functions and definitions
 *
 * @package CARES
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

define('WP_DEBUG', true);

if ( ! function_exists( 'cares_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function cares_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on CARES, use a find and replace
	 * to change 'cares' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'cares', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );
	
	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' 		=> __( 'Primary Menu', 'cares' ),
		'about-subnav' 	=> __( 'About Subnav Menu', 'cares' ),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	// Enable support for featured images - "post thumbnails"
	add_theme_support( 'post-thumbnails' );

	if ( function_exists( 'add_image_size' ) ) { 
		// Used as the post banner on the front page and single post pages
		add_image_size( 'featured-desktop', 1024, 400, true ); //(cropped)
		// Used for responsive images, eh? See inc/template-tags.php->cares_responsive_thumbnail() for implementation
		add_image_size( 'featured-300', 300, 200, true ); //(cropped)
		add_image_size( 'featured-450', 450, 225, true ); //(cropped)
		add_image_size( 'featured-600', 600, 300, true ); //(cropped)
		add_image_size( 'featured-800', 800, 350, true ); //(cropped)
	}

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'cares_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
		'caption',
	) );
}
endif; // cares_setup
add_action( 'after_setup_theme', 'cares_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function cares_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'cares' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer', 'cares' ),
		'id'            => 'footer-1',
		'description'   => 'Used for contact information in the footer',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'cares_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function cares_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_style( 'cares-style', get_stylesheet_uri() );

	wp_enqueue_script( 'cares-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
	
	//wp_register_script( 'cares-infinite-ajax', get_template_directory_uri() . '/js/cares-infinite-ajax.js' );
	//wp_enqueue_script( 'cares-infinite-ajax', get_template_directory_uri() . '/js/cares-infinite-ajax.js', 'jquery' );

	wp_enqueue_script( 'cares-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	wp_register_script(
		'cares-frontend-ajax',
		get_template_directory_uri().'/js/cares-frontend-ajax.js',
		array( 'jquery' ),
		false,
		false
	);
	
	//let js find the admin-ajax file
	wp_localize_script(
		'cares-frontend-ajax',
		'cares_ajax',
		array(
			'adminAjax' => admin_url( 'admin-ajax.php' )//,
			//'dashboardURL' => get_bloginfo( 'url' ) . NM_USER_DASH
		)
	);
	wp_enqueue_script('cares-frontend-ajax');
	
}
add_action( 'wp_enqueue_scripts', 'cares_scripts' );

/**
 * Specify "no-post-thumbnail" in post class, so we don't add margin then remove it if the post has a thumbnail.
 */	
function cares_no_thumbnail_class( $classes ) {
    if ( ! in_array( 'has-post-thumbnail', $classes ) )
        $classes[] = 'no-post-thumbnail';
    
    return $classes;
}
add_filter('post_class', 'cares_no_thumbnail_class', 98);

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
//moved to plugins - TODO: rethink strategy
//require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
//moved to plugins - TODO: rethink strategy
//require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load shiny admin user customizations.
 */
//moved to plugins
//require get_template_directory() . '/inc/admin.php';

/**
 * Load ajax functions file.
 */
//require_once( trailingslashit( get_template_directory() ). '/inc/cares-ajax.php');
require get_template_directory() . '/inc/cares-ajax.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

//set default image for profiles
define( 'CARES_PROFILE_IMG_DEFAULT', get_template_directory_uri() . '/img/profile_default.gif' );
