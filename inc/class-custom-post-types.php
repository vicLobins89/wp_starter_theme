<?php
/**
 * WP Starter Theme Custom Post Type Class
 *
 * @package WP_Starter_Theme
 * 
 * Credit: Gijs Jorissen
 * Tutorial URL: https://code.tutsplus.com/articles/custom-post-type-helper-class--wp-25104
 */

namespace WP_Starter_Theme;

class Custom_Post_Type {

    public $post_type_name;
    public $post_type_args;
    public $post_type_labels;

    public function __construct( $name, $args = array(), $labels = array() ){
        // Set vars
        $this->post_type_name = self::uglify( $name );
        $this->post_type_args = $args;
        $this->post_type_labels = $labels;

        // Action to register post type if it doesn't already exist
        if( !post_type_exists( $this->post_type_name ) ) {
            add_action( 'init', array( $this, 'register_post_type' ) );
        }
    }

    /**
     * Register new post type
     */
    public function register_post_type(){
        // Capitalise and make the name plural
        $name = self::beautify( $this->post_type_name );
        $plural = self::pluralise( $name );

        // Set default labels and overwrite with passed parameters
        $labels = array_merge(

            // Default
            array(
                'name'                  => _x( $plural, 'post type general name', 'wp_starter_theme' ),
                'singular_name'         => _x( $name, 'post type singular name', 'wp_starter_theme' ),
                'add_new'               => _x( 'Add New', strtolower( $name ), 'wp_starter_theme' ),
                'add_new_item'          => __( 'Add New ' . $name, 'wp_starter_theme' ),
                'edit_item'             => __( 'Edit ' . $name, 'wp_starter_theme' ),
                'new_item'              => __( 'New ' . $name, 'wp_starter_theme' ),
                'all_items'             => __( 'All ' . $plural, 'wp_starter_theme' ),
                'view_item'             => __( 'View ' . $name, 'wp_starter_theme' ),
                'search_items'          => __( 'Search ' . $plural, 'wp_starter_theme' ),
                'not_found'             => __( 'No ' . strtolower( $plural ) . ' found', 'wp_starter_theme' ),
                'not_found_in_trash'    => __( 'No ' . strtolower( $plural ) . ' found in Trash', 'wp_starter_theme' ),
                'parent_item_colon'     => '',
                'menu_name'             => $plural
            ),

            // Passed labels
            $this->post_type_labels

        );

        // Same as labels but for args
        $args = array_merge(

            // Default
            array(
                'label'                 => $name,
                'labels'                => $labels,
                'public'                => true,
                'show_ui'               => true,
                'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'comments', 'revisions' ),
                'show_in_nav_menus'     => true,
                '_builtin'              => false,
                'publicly_queryable'    => true,
                'exclude_from_search'   => false,
                'query_var'             => true,
                'rewrite'	            => array( 'slug' => self::uglify( $name ), 'with_front' => false ),
                'has_archive'           => self::uglify( $name ),
                'capability_type'       => 'post',
                'hierarchical'          => false,
            ),

            // Passed args
            $this->post_type_args

        );

        // Register
        register_post_type( $this->post_type_name, $args );
    }

    /**
     * Register taxonomies
     */
    public function add_taxonomy( $name, $args = array(), $labels = array() ){
        // Do nothing is $name is empty
        if( !empty( $name ) ) {
            // Get post type name for later
            $post_type_name = $this->post_type_name;

            // Taxonomy properties
            $taxonomy_name = self::uglify( $name );
            $taxonomy_args = $args;
            $taxonomy_labels = $labels;

            // Check if taxonomy exists
            if( !taxonomy_exists( $taxonomy_name ) ) {
                /* Create taxonomy and attach to post type */

                // Capitalise and make the name plural
                $name = self::beautify( $name );
                $plural = self::pluralise( $name );

                // Set default labels and overwrite with passed parameters
                $labels = array_merge(

                    // Default
                    array(
                        'name'                  => _x( $plural, 'taxonomy general name', 'wp_starter_theme' ),
                        'singular_name'         => _x( $name, 'taxonomy singular name', 'wp_starter_theme' ),
                        'search_items'          => __( 'Search ' . $plural, 'wp_starter_theme' ),
                        'all_items'             => __( 'All ' . $plural, 'wp_starter_theme' ),
                        'parent_item'           => __( 'Parent ' . $name, 'wp_starter_theme' ),
                        'parent_item_colon'     => __( 'Parent ' . $name . ':', 'wp_starter_theme' ),
                        'edit_item'             => __( 'Edit ' . $name, 'wp_starter_theme' ),
                        'update_item'           => __( 'Update ' . $name, 'wp_starter_theme' ),
                        'add_new_item'          => __( 'Add New ' . $name, 'wp_starter_theme' ),
                        'new_item_name'         => __( 'New ' . $name . ' Name', 'wp_starter_theme' ),
                        'menu_name'             => __( $name ),
                    ),

                    // Passed labels
                    $taxonomy_labels

                );

                // Same as labels but for args
                $args = array_merge(

                    // Default
                    array(
                        'hierarchical'      => true,
                        'label'             => $plural,
                        'labels'            => $labels,
                        'public'            => true,
                        'show_ui'           => true,
                        'show_in_nav_menus' => true,
                        'show_admin_column' => true, 
                        'query_var'         => true,
			            'rewrite'           => array( 'slug' => self::uglify( $name ), 'with_front' => false ),
                    ),

                    // Passed args
                    $taxonomy_args

                );

                // Add taxonomy to post type
                // use() function passes vars to nameless function
                add_action( 'init', function() use( $taxonomy_name, $post_type_name, $args ){
                    register_taxonomy( $taxonomy_name, $post_type_name, $args );
                } );
            } else {
                /* Attach existing taxonomy to object type (post type) */
                add_action( 'init', function() use( $taxonomy_name, $post_type_name ){
                    register_taxonomy_for_object_type( $taxonomy_name, $post_type_name );
                } );
            }
        }
    }

    /**
     * Helper function to optimise code
     */
    public static function beautify( $string ){
        return ucwords( str_replace( '-', ' ', $string ) );
    }

    public static function uglify( $string ){
        return strtolower( str_replace( ' ', '-', $string ) );
    }

    public static function pluralise( $string ){
        $last = $string[strlen( $string ) - 1];

        if( $last == 'y' ) {
            $cut = substr( $string, 0, -1 );
            $plural = $cut . 'ies';
        } elseif( $last == 's' ) {
            $plural = $string;
        } else {
            $plural = $string . 's';
        }

        return $plural;
    }
}