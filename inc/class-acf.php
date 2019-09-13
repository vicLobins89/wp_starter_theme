<?php
/**
 * Class created to render ACF fields within theme
 *
 * @package WP_Starter_Theme
 */

namespace WP_Starter_Theme;

class Custom_ACF {
    /**
     * Render theme settings page
     */
    public static function render_options_page(){
        if( function_exists('acf_add_options_page') ) {
	
            acf_add_options_page(array(
                'page_title' 	=> 'Theme General Settings',
                'menu_title'	=> 'Theme Settings',
                'menu_slug' 	=> 'theme-general-settings',
                'position'      => '28.35',
                'capability'	=> 'edit_posts',
                'redirect'		=> false
            ));
        }
    }
    
    /**
     * Render social media links
     */
    public static function render_social_media(){
        if( have_rows('social_media', 'option') ) : ?>
            <div class="social-links">

            <?php
            while( have_rows('social_media', 'option') ): the_row(); ?>

                <a href="<?php the_sub_field('link'); ?>" target="_blank"><?php the_sub_field('icon'); ?></a>

            <?php
            endwhile; ?>

            </div>
            <?php
        endif; // end social media row
    }

    /**
     * Helper function to create a classes array for use in flexible content ACF fields
     */
    private static function get_classes($container){
        $all_classes = array();
        
        // Pushing layout values to classes array
        $layout = $container['layout'];
        if( $layout ) {
            array_push($all_classes, $layout);
        }
        
         // Pushing classes to array
        $custom_class = $container['class'];
        if( $custom_class ) {
            array_push($all_classes, $custom_class);
        }

        return $all_classes;
    }

    /**
     * Render flexi content ACF fields for use in template files
     */
    public static function render_content() {
        $all_styles = array();
        $styles = '';
        $classes = '';

        // check if the flexible content field has rows of data
        if( have_rows('page_content') ):
        // loop through the rows of data
        while ( have_rows('page_content') ) : the_row();

            if( get_row_layout() == 'content' ) : 
                ob_start();
                $container = get_sub_field('container');
                $all_classes = Custom_ACF::get_classes($container);

                // Getting BG fields and pushing to classes/styles array
                $background = get_sub_field('background');
                $bg_color = $background['bg_color'];
                $bg_image = $background['bg_image'];
                if( $bg_color ) {
                    array_push($all_classes, "bg-colour");
                    array_push($all_styles, "background-color: $bg_color;");
                }
                if( $bg_image ) {
                    array_push($all_styles, "background-image: url('$bg_image');");
                    array_push($all_styles, "background-repeat: no-repeat;");
                    array_push($all_styles, "background-size: cover;");
                    array_push($all_styles, "background-position: center;");
                }

                // Getting Padding fields and pushing to styles array
                $padding = get_sub_field('padding');
                if( $padding ) {
                    if( $padding['padding_top'] ) { array_push($all_styles, "padding-top: $padding[padding_top];"); }
                    if( $padding['padding_right'] ) { array_push($all_styles, "padding-right: $padding[padding_right];"); }
                    if( $padding['padding_bottom'] ) { array_push($all_styles, "padding-bottom: $padding[padding_bottom];"); }
                    if( $padding['padding_left'] ) { array_push($all_styles, "padding-left: $padding[padding_left];"); }
                }

                // Creating ID var to echo
                $custom_id = '';
                if( $container['id'] ) {
                    $custom_id = ' id="'.$container['id'].'"';
                }

                // Creating classes var to echo
                if( isset($all_classes) && !empty($all_classes) ) {
                    $classes = implode(" ", $all_classes);
                }

                // Creating styles var to echo
                if( isset($all_styles) && !empty($all_styles) ) {
                    $styles = ' style="';
                    $styles .= implode(" ", $all_styles);
                    $styles .= '"';
                }

                echo '<section '.$custom_id.' class="row row__custom-content '.$classes.'"'.$styles.'>
                <div class="clearfix">';

                $columns = array();
                if( get_sub_field('col_1') ) {
                    array_push($columns, 'col_1');
                }
                if( get_sub_field('col_2') ) {
                    array_push($columns, 'col_2');
                }
                if( get_sub_field('col_3') ) {
                    array_push($columns, 'col_3');
                }
                if( get_sub_field('col_4') ) {
                    array_push($columns, 'col_4');
                }

                // Loop over columns to create even sized divs
                $col_num = count(array_filter($columns));
                foreach($columns as $column) {
                    if( $column ) {
                        // Setup width sub field for each column
                        $width_subfield = $column . '_w';
                        $col_width = '';
                        if( get_sub_field($width_subfield) ) {
                            $col_width = 'data-width="'.get_sub_field($width_subfield).'%"';
                        }
                        // Display columns
                        echo '<div '.$col_width.' class="col-'.(12/$col_num).'">' . get_sub_field($column) . '</div>';
                    }
                }

                echo '</div>
                </section>';
                $content = ob_get_contents();
                ob_end_clean();
                echo $content;
            // end custom content

            elseif( get_row_layout() == 'gallery' ) :
                ob_start();
                $images = get_sub_field('gallery');
                $container = get_sub_field('container');
                $all_classes = Custom_ACF::get_classes($container);

                // Creating ID var to echo
                $custom_id = '';
                if( $container['id'] ) {
                    $custom_id = ' id="'.$container['id'].'"';
                }

                // Creating classes var to echo
                if( isset($all_classes) && !empty($all_classes) ) {
                    $classes = implode(" ", $all_classes);
                }

                if( $images ): ?>
                <?php echo '<section '.$custom_id.' class="row row__gallery '.$classes.'">'; ?>
                <div class="clearfix">
                    <ul>
                        <?php foreach( $images as $image ): ?>
                        <li>
                            <?php echo wp_get_attachment_image( $image['ID'], 'large' ); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                </section>
                <?php 
                $content = ob_get_contents();
                ob_end_clean();
                echo $content;
                endif;
            // end gallery

            elseif( get_row_layout() == 'blog_feed' ) :
                ob_start();
                $all_cats = get_sub_field('all_categories');
                $post_category = get_sub_field('category');
                $post_num = get_sub_field('post_count');
                $container = get_sub_field('container');
                $all_classes = Custom_ACF::get_classes($container);

                // Creating ID var to echo
                $custom_id = '';
                if( $container['id'] ) {
                    $custom_id = ' id="'.$container['id'].'"';
                }

                // Creating classes var to echo
                if( isset($all_classes) && !empty($all_classes) ) {
                    $classes = implode(" ", $all_classes);
                }

                // Setup args for either all categories or selected in sub field
                global $post;
                if( $all_cats == true ) {
                    $args = array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'posts_per_page' => $post_num,
                    );
                } else {
                    $args = array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'cat' => $post_category,
                        'posts_per_page' => $post_num,
                    );
                }
                $arr_posts = new \WP_Query( $args );
                
                // Display blog feed
                if ( $arr_posts->have_posts() ) : ?>
                    <?php echo '<section '.$custom_id.' class="row row__blog-feed '.$classes.'">'; ?>
                    <div class="clearfix">
                        <?php 
                        while ( $arr_posts->have_posts() ) :
                            $arr_posts->the_post(); 
                            get_template_part( 'template-parts/content', 'post' );
                        endwhile; ?>
                    </div>
                    </section>
                <?php endif;
                $content = ob_get_contents();
                ob_end_clean();
                echo $content;
                wp_reset_postdata();
                // end blog feed
                
            endif; // end page_content if
        endwhile; // end page_content loop
        else :
        // no layouts found
        endif;
    }
}