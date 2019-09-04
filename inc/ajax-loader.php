<?php
/**
 * WP Starter Theme AJAX posts loader
 *
 * @package WP_Starter_Theme
 */

namespace WP_Starter_Theme\Ajax_Loader;

 /**
 * Enqueue ajax script and add wp paramenters
 */
function wp_starter_theme_load_scripts(){
    global $wp_query;

    wp_enqueue_script('jquery');

    // Register main script
    wp_register_script( 'ajax-loader', get_stylesheet_directory_uri() . '/assets/js/theme/ajax-loader.js', array('jquery') );

    // Adding params to be available in script
    wp_localize_script( 'ajax-loader', 'wp_starter_theme_params', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ), //WP Ajax
        'posts' => json_encode( $wp_query->query_vars ), // the loop
        'current_page' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
        'max_page' => $wp_query->max_num_pages
    ) );

    wp_enqueue_script( 'ajax-loader' );
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\wp_starter_theme_load_scripts' );

/**
 * HTML for the category filter
 */
function render_categories(){
    ?>
    <form action="#" id="filter">

        <?php
        if( $terms = get_terms( array(
            'taxonomy' => 'category',
            'orderby' => 'name'
        ) ) ) {
            // if categories exist display html
            echo '<select name="categoryfilter" id="categoryfilter">
            <option value="">Select category...</option>';
            foreach( $terms as $term ){
                echo '<option value="' . $term->term_id . '">' . $term->name . '</option>';
            }
            echo '</select>';
        }
        ?>

        <?php /* name the action 'myfilter' for ajax */ ?>
        <input type="hidden" name="action" value="myfilter">
        <button>Apply filter</button>
    </form>
    <?php
}

/**
 * Handler to render post content for load more button
 */
function wp_starter_theme_loadmore_handler(){
    $args = json_decode( stripslashes( $_POST['query'] ), true );
    $args['paged'] = $_POST['page'] + 1; // load next page
    $args['post_status'] = 'publish';

    query_posts( $args );

    if( have_posts() ) {
        while( have_posts() ) {
            the_post();
            get_template_part( 'template-parts/content', 'post' );
        }
    }
    die; // exit script
}
add_action('wp_ajax_loadmore', __NAMESPACE__ . '\\wp_starter_theme_loadmore_handler');
add_action('wp_ajax_nopriv_loadmore', __NAMESPACE__ . '\\wp_starter_theme_loadmore_handler');

/**
 * Handler to render post content for the filter
 */
function wp_starter_theme_filter_handler(){
    // Cats
    if( isset( $_POST['categoryfilter'] ) ) {
        $args['tax_query'] = array(
			array(
				'taxonomy' => 'category',
				'field' => 'id',
				'terms' => $_POST['categoryfilter']
			)
		);
    }

    // query posts
    query_posts( $args );
    global $wp_query;

    if( have_posts() ) {
        // start buffering and save to var
        ob_start();

        while( have_posts() ) {
            the_post();
            get_template_part( 'template-parts/content', 'post' );
        }

        $html = ob_get_contents();
        ob_end_clean();
    } else {
        $html = '<p>Nothing found for your criteria.</p>';
    }

    // no wp_reset_query() required
    echo json_encode( array(
        'posts' => json_encode( $wp_query->query_vars ),
        'max_page' => $wp_query->max_num_pages,
        'found_posts' => $wp_query->found_posts,
        'content' => $html
    ) );

    die();
}
add_action('wp_ajax_myfilter', __NAMESPACE__ . '\\wp_starter_theme_filter_handler'); 
add_action('wp_ajax_nopriv_myfilter', __NAMESPACE__ . '\\wp_starter_theme_filter_handler');