<?php
/**
 * WP Starter Theme AJAX posts loader
 *
 * @package WP_Starter_Theme
 */

namespace WP_Starter_Theme;

class Ajax_Loader {

    public function __construct() {
        add_action('wp_enqueue_scripts', array( $this, 'load_scripts' ) );

        add_action('wp_ajax_loadmore', array( $this, 'loadmore_handler' ) );
        add_action('wp_ajax_nopriv_loadmore', array( $this, 'loadmore_handler' ) );

        add_action('wp_ajax_myfilter', array( $this, 'filter_handler' ) ); 
        add_action('wp_ajax_nopriv_myfilter', array( $this, 'filter_handler' ) );

        add_action( 'pre_get_posts', array( $this, 'modify_query' ) );
    }

     /**
     * Enqueue ajax script and add wp paramenters
     */
    public function load_scripts() {
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

    /**
     * HTML for the category filter
     */
    public static function render_categories( $taxonomy = 'category', $post_type = 'post' ){
        ?>
        <form action="#" id="filter">
            <?php
            if( $terms = get_terms( array(
                'taxonomy' => $taxonomy,
                'orderby' => 'name'
            ) ) ) {
                // if categories exist display html
                echo '<p>Select category...</p>
                <label class="post-filter-label" for="filter-all">All</label>
                <input type="radio" class="post-filter" id="filter-all" name="categoryfilter" value="all">';
                foreach( $terms as $term ){
                    echo '<label class="post-filter-label" for="filter-' . $term->slug . '">' . $term->name . '</label>
                    <input type="radio" class="post-filter" id="filter-' . $term->slug . '" name="categoryfilter" value="' . $term->slug . '">';
                }
                echo '<input type="hidden" name="taxonomy" value="'.$taxonomy.'">';
                echo '<input type="hidden" name="post_type" value="'.$post_type.'">';
            }
            ?>

            <?php /* name the action 'myfilter' for ajax */ ?>
            <input type="hidden" name="action" value="myfilter">
        </form>
        <?php
    }

    /**
     * Handler to render post content for load more button
     */
    public function loadmore_handler(){
        $args = json_decode( stripslashes( $_POST['query'] ), true );
        $args['paged'] = $_POST['page'] + 1; // load next page
        $args['post_status'] = 'publish';

        query_posts( $args );

        if( have_posts() ) {
            while( have_posts() ) {
                the_post();
                get_template_part( 'template-parts/content', get_post_type() );
            }
        }
        die; // exit script
    }

    /**
     * Handler to render post content for the filter
     */
    public function filter_handler(){
        if( isset( $_POST['categoryfilter'] ) && $_POST['categoryfilter'] !== 'all' ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => $_POST['taxonomy'],
                    'field' => 'slug',
                    'terms' => $_POST['categoryfilter']
                )
            );
        } else if( $_POST['categoryfilter'] == 'all' ) {
            $args['post_type'] = $_POST['post_type'];
            $args['post_status'] = 'publish';
        }

        // query posts
        query_posts( $args );
        global $wp_query;

        if( have_posts() ) {
            // start buffering and save to var
            ob_start();

            while( have_posts() ) {
                the_post();
                get_template_part( 'template-parts/content', get_post_type() );
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
            'content' => $html,
            'category' => $wp_query->queried_object->slug,
            'post_type' => $_POST['post_type']
        ) );

        die();
    }

    /**
     * Pre get posts action to modify query for use in custom posts and filters
     */
    public function modify_query( $query ) {
        if( $query->is_main_query() && isset( $_GET['query'] ) ) {
            $taxonomy = get_object_taxonomies( $_GET['query'] );

            if( isset( $_GET['filter'] ) ) {
    
                $taxquery = array(
                    array(
                        'taxonomy' => $taxonomy[0],
                        'field' => 'slug',
                        'terms' => $_GET['filter']
                    )
                );
    
                $query->set( 'tax_query', $taxquery );
            }
        }
    }
    
}

// Initialise class
$ajax_loader = new Ajax_Loader();