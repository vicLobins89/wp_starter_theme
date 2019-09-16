<?php
/**
 * The main template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WP_Starter_Theme
 */

use WP_Starter_Theme\Ajax_Loader;
use WP_Starter_Theme\Tags as Tags;

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main clearfix">

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;

			/* Cats */
			Ajax_Loader::render_categories();

			/* Start the Loop */
			?>
			<div class="posts-container flex flex-wrap clearfix">
			<?php
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'post' );

			endwhile; ?>
			</div>

			<?php
			// Display the load more button if there are enough posts and Ajaxify is enabled in theme settings
			if( get_field('ajaxify', 'option') && $wp_query->max_num_pages > 1 ) {
				echo '<div class="primary-btn load-more">More posts</div>';
			} else {
				Tags\the_posts_navigation();
			}

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
