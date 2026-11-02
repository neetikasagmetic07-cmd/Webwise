<?php
$footer_logo     = webwise_get_field( 'footer_logo' );
$footer_desc     = webwise_get_field( 'footer_description' );
$footer_services = webwise_get_field( 'footer_services' );
$social_links    = webwise_get_field( 'footer_social_links' );
$payment_logos   = webwise_get_field( 'footer_payment_logos' );
$copyright       = webwise_get_field( 'footer_copyright' );
$support_link    = webwise_get_field( 'footer_support_link' );

$footer_services = is_array( $footer_services ) ? array_values( array_filter( $footer_services, static function ( $item ) {
	return is_array( $item ) && (
		! empty( $item['icon'] )
		|| ! empty( $item['title'] )
		|| ! empty( $item['copy'] )
		|| ! empty( $item['button_label'] )
		|| ! empty( $item['input_placeholder'] )
		|| ! empty( $item['link'] )
	);
} ) ) : array();
$social_links = is_array( $social_links ) ? array_values( array_filter( $social_links, static function ( $item ) {
	return is_array( $item ) && ! empty( $item['name'] ) && ! empty( $item['link']['url'] );
} ) ) : array();
$payment_logos = is_array( $payment_logos ) ? array_values( array_filter( $payment_logos, static function ( $item ) {
	return is_array( $item ) && ! empty( $item['image'] );
} ) ) : array();
?>

<footer class="site-footer">
	<?php if ( is_array( $footer_services ) && ! empty( $footer_services ) ) : ?>
		<div class="footer-service-panels content-shell">
			<?php foreach ( $footer_services as $service_index => $service_panel ) : ?>
				<?php
				$panel_icon              = $service_panel['icon'] ?? 0;
				$panel_title             = $service_panel['title'] ?? '';
				$panel_copy              = $service_panel['copy'] ?? '';
				$panel_link              = $service_panel['link'] ?? array();
				$panel_button_label      = $service_panel['button_label'] ?? '';
				$panel_input_placeholder = $service_panel['input_placeholder'] ?? '';
				?>
				<section class="footer-service-panel">
					<?php echo webwise_render_image( $panel_icon, 'thumbnail', 'footer-service-icon' ); ?>
					<?php if ( $panel_title ) : ?>
						<h2><?php echo esc_html( $panel_title ); ?></h2>
					<?php endif; ?>
					<?php if ( $panel_copy ) : ?>
						<p><?php echo nl2br( esc_html( $panel_copy ) ); ?></p>
					<?php endif; ?>
					<?php if ( $panel_input_placeholder ) : ?>
						<div class="footer-newsletter">
							<input
								id="<?php echo esc_attr( 'footer-service-email-' . $service_index ); ?>"
								type="email"
								placeholder="<?php echo esc_attr( $panel_input_placeholder ); ?>"
								aria-label="<?php echo esc_attr( $panel_input_placeholder ); ?>"
							>
							<?php if ( $panel_button_label ) : ?>
								<button class="footer-service-link" type="button"><?php echo esc_html( $panel_button_label ); ?></button>
							<?php endif; ?>
						</div>
					<?php elseif ( $panel_button_label || ! empty( $panel_link ) ) : ?>
						<?php echo webwise_render_labeled_link( $panel_button_label, $panel_link, 'footer-service-link' ); ?>
					<?php endif; ?>
				</section>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div class="footer-main content-shell">
		<div class="footer-brand">
			<?php if ( $footer_logo ) : ?>
				<?php echo webwise_render_image( $footer_logo, 'medium', 'footer-logo' ); ?>
			<?php endif; ?>
			<?php if ( $footer_desc ) : ?>
				<p><?php echo nl2br( esc_html( $footer_desc ) ); ?></p>
			<?php endif; ?>
		</div>

		<?php
		$menu_columns = array(
			array(
				'location' => 'footer-left',
				'heading'  => webwise_get_field( 'footer_quick_links_heading' ),
			),
			array(
				'location' => 'footer-menu-2',
				'heading'  => webwise_get_field( 'footer_legal_links_heading' ),
			),
			array(
				'location' => 'footer-menu-3',
				'heading'  => webwise_get_field( 'footer_account_links_heading' ),
			),
		);
		?>
		<?php foreach ( $menu_columns as $menu_column ) : ?>
			<?php
			$menu_html = wp_nav_menu(
				array(
					'theme_location' => $menu_column['location'],
					'container'      => false,
					'menu_class'     => 'footer-menu',
					'fallback_cb'    => false,
					'depth'          => 1,
					'echo'           => false,
				)
			);
			?>
			<?php if ( $menu_html ) : ?>
				<nav class="footer-link-column" aria-label="<?php echo esc_attr( $menu_column['heading'] ? $menu_column['heading'] : wp_get_nav_menu_name( $menu_column['location'] ) ); ?>">
					<?php if ( $menu_column['heading'] ) : ?>
						<h2><?php echo esc_html( $menu_column['heading'] ); ?></h2>
					<?php endif; ?>
					<?php echo $menu_html; ?>
				</nav>
			<?php endif; ?>
		<?php endforeach; ?>

		<?php if ( webwise_get_field( 'footer_social_heading' ) || ( is_array( $social_links ) && ! empty( $social_links ) ) ) : ?>
			<nav class="footer-link-column footer-social-links" aria-label="<?php echo esc_attr( webwise_get_field( 'footer_social_heading' ) ?: __( 'Social media', 'twentytwentyfour-child' ) ); ?>">
				<?php $social_heading = webwise_get_field( 'footer_social_heading' ); ?>
				<?php if ( $social_heading ) : ?>
					<h2><?php echo esc_html( $social_heading ); ?></h2>
				<?php endif; ?>
				<ul>
					<?php foreach ( $social_links as $social_item ) : ?>
						<?php
						$social_name = $social_item['name'] ?? '';
						$social_link = $social_item['link'] ?? array();
						?>
						<?php if ( $social_name && ! empty( $social_link['url'] ) ) : ?>
							<li>
								<a <?php echo webwise_link_attributes( $social_link ); ?>><?php echo esc_html( $social_name ); ?></a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endif; ?>
	</div>

	<?php if ( webwise_get_field( 'footer_payment_heading' ) || ( is_array( $payment_logos ) && ! empty( $payment_logos ) ) ) : ?>
		<div class="footer-payments content-shell">
			<?php $payment_heading = webwise_get_field( 'footer_payment_heading' ); ?>
			<?php if ( $payment_heading ) : ?>
				<h2><?php echo esc_html( $payment_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( is_array( $payment_logos ) && ! empty( $payment_logos ) ) : ?>
				<div class="payment-logo-list">
				<?php foreach ( $payment_logos as $payment_logo ) : ?>
					<?php echo webwise_render_image( $payment_logo['image'] ?? 0, 'thumbnail', 'payment-logo' ); ?>
				<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $copyright ) : ?>
		<div class="footer-bottom content-shell">
			<p><?php echo esc_html( $copyright ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $support_link['url'] ) ) : ?>
		<a <?php echo webwise_link_attributes( $support_link, 'floating-support-link' ); ?>>
			<span aria-hidden="true">?</span>
			<span class="screen-reader-text"><?php echo esc_html( $support_link['title'] ? $support_link['title'] : __( 'Support', 'twentytwentyfour-child' ) ); ?></span>
		</a>
	<?php endif; ?>
</footer>
<?php wp_footer(); ?>
</body>
</html>
