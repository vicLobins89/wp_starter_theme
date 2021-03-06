<?php
/**
 * WP Starter Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WP_Starter_Theme
 */

namespace WP_Starter_Theme\Core;

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on WP Starter Theme, use a find and replace
		* to change 'wp_starter_theme' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'wp_starter_theme', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// default thumb size
	set_post_thumbnail_size(125, 125, true);

	// This theme uses wp_nav_menu()
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'wp_starter_theme' ),
	) );
	register_nav_menus( array(
		'menu-2' => esc_html__( 'Secondary', 'wp_starter_theme' ),
	) );
	register_nav_menus( array(
		'menu-3' => esc_html__( 'Footer', 'wp_starter_theme' ),
	) );

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 250,
		'width'       => 250,
		'flex-width'  => true,
		'flex-height' => true,
	) );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( __NAMESPACE__ . '\content_width', 640 );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'wp_starter_theme' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'wp_starter_theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', __NAMESPACE__ . '\widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function scripts() {
	if (!is_admin()) {
		wp_enqueue_style( 'wp_starter_stylesheet', get_template_directory_uri() . '/assets/css/style.css', array(), '', 'all' );
	}

	wp_enqueue_script( 'wp_starter_theme-scripts', get_template_directory_uri() . '/assets/js/main.min.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Adding Custom ACF Class
 */
require get_template_directory() . '/inc/class-acf.php';
use WP_Starter_Theme\Custom_ACF;

// Theme settings page
Custom_ACF::render_options_page();

/**
 * Adding AJAX loading for posts if enabled
 */
if( get_field('ajaxify', 'option') ) {
	require get_template_directory() . '/inc/class-ajax-loader.php';
}

/**
 * Adding Custom Post Types
 */
require get_template_directory() . '/inc/class-custom-post-types.php';
use WP_Starter_Theme\Custom_Post_Type;

// Set slug overwrite args for post type
$case_study_args = array(
	'rewrite' => array( 'slug' => 'case-studies', 'with_front' => false ),
	'has_archive' => 'case-studies',
);

// Initialise class
$case_study = new Custom_Post_Type( 'Project', $case_study_args );
$case_study->add_taxonomy( 'case-studies' );