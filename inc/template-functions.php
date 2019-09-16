<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package WP_Starter_Theme
 */

namespace WP_Starter_Theme\Helpers;

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', __NAMESPACE__ . '\body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', __NAMESPACE__ . '\\pingback_header' );


/**
 * Adding custom thumbnail sizes
 */
// Thumbnail sizes
add_image_size( 'folio-portrait', 600, 800, true );
add_image_size( 'folio-thumb', 420, 300, true );
add_image_size( 'square', 500, 500, true );

function custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'folio-portrait' => __('600px by 800px'),
        'folio-thumb' => __('420px by 300px'),
        'square' => __('500px Square'),
    ) );
}
add_filter( 'image_size_names_choose', __NAMESPACE__ . '\\custom_image_sizes' );