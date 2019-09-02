<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Starter_Theme
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="site-info">
			<?php
			// Get ACF copyright field or fallback to site name and year
			if( get_field('copyright', 'option') ) {
				echo '<p class="copyright">'.get_field('copyright', 'option').'</p>';
			} else {
				echo '<p class="copyright">@ Copyright ' . get_bloginfo() . ' ' . date('Y').'</p>';
			}
			?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>