<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WP_Starter_Theme
 */

use WP_Starter_Theme\Ajax_Loader;

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
			/* Cats */
			Ajax_Loader::render_categories( 'case-studies', 'project' );

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
				get_template_part( 'template-parts/content', get_post_type() );

			endwhile; ?>
			</div>

			<?php
			// Display the load more button if there are enough posts and Ajaxify is enabled in theme settings
			if( get_field('ajaxify', 'option') && $wp_query->max_num_pages > 1 ) {
				echo '<div class="primary-btn load-more">More posts</div>';
			} else {
				wp_starter_theme_the_posts_navigation();
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
