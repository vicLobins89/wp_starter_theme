<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WP_Starter_Theme
 */

use WP_Starter_Theme\Custom_ACF;
use WP_Starter_Theme\Tags as Tags;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	$thecontent = get_the_content();
	if( !empty($thecontent) || has_post_thumbnail() ) : ?>
		<header class="entry-header row row__header<?php echo ' '.get_field('layout'); ?>">
			<?php if( has_post_thumbnail() ) : Tags\post_thumbnail(); endif; // .post-thumbnail

			if( !empty($thecontent) ) : ?>
			<div class="header-content clearfix">
				<?php the_content(); ?>
			</div>
			<?php
			endif; ?><!-- .header-content -->
		</header><!-- .entry-header -->
		<?php
	endif; ?>
	
	<div class="entry-content">
	<?php
	Custom_ACF::render_content();

	wp_link_pages( array(
		'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wp_starter_theme' ),
		'after'  => '</div>',
	) );
	?>	
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'wp_starter_theme' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
