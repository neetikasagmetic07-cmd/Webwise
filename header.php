<?php
$logo_id       = webwise_get_field( 'header_logo' );
$logo_text     = webwise_get_field( 'header_logo_text' );
$header_button = webwise_get_field( 'header_button' );
$site_label    = $logo_text ? $logo_text : get_bloginfo( 'name' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
	<div class="site-header-inner">
		<nav class="primary-navigation" aria-label="<?php echo esc_attr( wp_get_nav_menu_name( 'header-menu-1' ) ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'header-menu-1',
					'container'      => false,
					'menu_class'     => 'header-menu-list',
					'fallback_cb'    => false,
					'depth'          => 1,
				)
			);
			?>
		</nav>

		<a class="site-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $site_label ); ?>">
			<?php if ( $logo_id ) : ?>
				<?php echo webwise_render_image( $logo_id, 'medium', 'site-brand-image' ); ?>
			<?php elseif ( $logo_text ) : ?>
				<span><?php echo esc_html( $logo_text ); ?></span>
			<?php elseif ( has_custom_logo() ) : ?>
				<?php echo webwise_render_image( get_theme_mod( 'custom_logo' ), 'medium', 'site-brand-image' ); ?>
			<?php endif; ?>
		</a>

		<nav class="header-utilities" aria-label="<?php echo esc_attr( wp_get_nav_menu_name( 'header-menu-2' ) ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'header-menu-2',
					'container'      => false,
					'menu_class'     => 'header-menu-list',
					'fallback_cb'    => false,
					'depth'          => 1,
				)
			);
			?>
			<?php if ( ! empty( $header_button ) ) : ?>
				<?php echo webwise_render_link( $header_button, 'header-button' ); ?>
			<?php endif; ?>
		</nav>
	</div>
</header>
