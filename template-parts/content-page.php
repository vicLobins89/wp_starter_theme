<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WP_Starter_Theme
 */
require_once(get_template_directory() . '/inc/class-acf.php');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	$thecontent = get_the_content();
	if( has_post_thumbnail() ) : ?>
		<header class="entry-header">
			<?php wp_starter_theme_post_thumbnail(); ?>
		</header><!-- .entry-header -->
		<?php
	endif; ?>
	
	<div class="entry-content">
	<?php
	if( !empty($thecontent) ) :
		?>
		<section class="row row__main-content <?php echo get_field('layout'); ?>">
		<div class="clearfix">
			<?php the_content(); ?>
		</div>
		</section>
	<?php
	endif; ?>

	<?php
	WP_Starter_Theme\CustomACF\acf::render_content();

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
