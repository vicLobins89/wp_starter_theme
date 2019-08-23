<?php
/**
 * Class created to render ACF fields within theme
 *
 * @package WP_Starter_Theme
 */

namespace WP_Starter_Theme\CustomACF;

class acf {
    static function render_content() {
        // check if the flexible content field has rows of data
        if( have_rows('page_content') ):
        // loop through the rows of data
        while ( have_rows('page_content') ) : the_row();

            if( get_row_layout() == 'content' ) : ?>
                <section class="clearfix">
                <?php
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
                foreach($columns as $key => $column) {
                    if( $column ) {
                        // Setup width sub field for each column
                        $width_subfield = $column . '_w';
                        $col_width = '';
                        if( get_sub_field($width_subfield) ) {
                            $col_width = 'style="width:'.get_sub_field($width_subfield).'%  "';
                        }
                        // Display columns
                        echo '<div '.$col_width.' class="col-'.(12/$col_num).'">' . get_sub_field($column) . '</div>';
                    }
                } ?>
                </section>
                <?php
            // end custom content
            elseif(get_row_layout() == 'gallery'):
                $images = get_sub_field('gallery');
                if( $images ): ?>
                <section class="clearfix">
                    <ul>
                        <?php foreach( $images as $image ): ?>
                        <li>
                            <?php echo wp_get_attachment_image( $image['ID'], 'large' ); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
                <?php endif;
            // end gallery
            elseif(get_row_layout() == 'blog_feed'):
                $all_cats = get_sub_field('all_categories');
                $post_category = get_sub_field('category');
                $post_num = get_sub_field('post_count');

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
                    <section class="clearfix">
                    <?php while ( $arr_posts->have_posts() ) :
                        $arr_posts->the_post(); ?>
                        <div id="post-<?php the_ID(); ?>" class="col-<?php echo (12/$post_num); ?> cf">
                            <a href="<?php the_permalink(); ?>" class="thumb"><?php the_post_thumbnail('square'); ?></a>
                            <div class="text">
                                <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="excerpt"><?php the_excerpt(); ?></p>
                                <a class="read-more" href="<?php the_permalink(); ?>">Read More</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    </section>
                <?php endif;
                wp_reset_postdata();

            endif;
        endwhile; // end page_content loop
        else :
        // no layouts found
        endif;
    }
}