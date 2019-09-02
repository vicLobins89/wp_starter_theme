<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Starter_Theme
 */

use WP_Starter_Theme\Custom_ACF;

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php // favicons ?>
	<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.png">
	<!--[if IE]>
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.ico">
	<![endif]-->

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text visually-hidden" href="#content"><?php esc_html_e( 'Skip to content', 'wp_starter_theme' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-branding">
			<?php
			// Get ACF Theme options logo or Customizer logo. If not set use site name
			if( get_field('logo', 'option') ) :
				$logo = get_field('logo', 'option');
				echo '<a class="logo" href="'.esc_url( home_url( '/' ) ).'" rel="home"><img src="'.$logo['url'].'" alt="'.$logo['alt'].'"></a>';
			elseif( get_custom_logo() ) :
				the_custom_logo();
			else : 
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php 
			endif;
			?>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu"><?php esc_html_e( 'Primary Menu', 'wp_starter_theme' ); ?></button>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
			) );
			?>
		</nav><!-- #site-navigation -->

		<?php Custom_ACF::render_social_media(); ?>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
