<?php
/**
 * Template Name: About
 * Description: About page with ACF-managed sections.
 */

get_header();

$hero_image             = webwise_get_page_field( 'about_hero_image' );
$hero_heading            = webwise_get_page_field( 'about_hero_heading' );
$intro_image             = webwise_get_page_field( 'about_intro_image' );
$intro_heading            = webwise_get_page_field( 'about_intro_heading' );
$intro_paragraph_one      = webwise_get_page_field( 'about_intro_paragraph_one' );
$intro_paragraph_two      = webwise_get_page_field( 'about_intro_paragraph_two' );
$philosophy_heading       = webwise_get_page_field( 'about_philosophy_heading' );
$philosophy_copy          = webwise_get_page_field( 'about_philosophy_copy' );
$philosophy_cards         = webwise_get_page_field( 'about_philosophy_cards' );
$services_intro           = webwise_get_page_field( 'about_services_intro' );
$services_heading         = webwise_get_page_field( 'about_services_heading' );
$services                 = webwise_get_page_field( 'about_services' );
$campaign_image           = webwise_get_page_field( 'about_campaign_image' );
$campaign_heading         = webwise_get_page_field( 'about_campaign_heading' );
$press_intro              = webwise_get_page_field( 'about_press_intro' );
$press_heading            = webwise_get_page_field( 'about_press_heading' );
$press_logos              = webwise_get_page_field( 'about_press_logos' );
$social_heading           = webwise_get_page_field( 'about_social_heading' );
$social_images            = webwise_get_page_field( 'about_social_images' );

$philosophy_cards = is_array( $philosophy_cards ) ? array_values( array_filter( $philosophy_cards, static function ( $card ) {
	return is_array( $card ) && ( ! empty( $card['icon'] ) || ! empty( $card['title'] ) || ! empty( $card['copy'] ) );
} ) ) : array();
$services = is_array( $services ) ? array_values( array_filter( $services, static function ( $service ) {
	return is_array( $service ) && (
		! empty( $service['image'] )
		|| ! empty( $service['title'] )
		|| ! empty( $service['copy'] )
		|| ! empty( $service['cta_label'] )
	);
} ) ) : array();
$press_logos = is_array( $press_logos ) ? array_values( array_filter( $press_logos, static function ( $logo ) {
	return is_array( $logo ) && ( ! empty( $logo['image'] ) || ! empty( $logo['link'] ) );
} ) ) : array();
$social_images = is_array( $social_images ) ? array_values( array_filter( $social_images, static function ( $item ) {
	return is_array( $item ) && ! empty( $item['image'] );
} ) ) : array();
?>

<main id="primary" class="site-main about-page">
	<?php if ( $hero_image || $hero_heading ) : ?>
		<section class="about-hero">
			<?php echo webwise_render_image( $hero_image, 'full', 'about-hero-image' ); ?>
			<div class="about-hero-shade"></div>
			<?php if ( $hero_heading ) : ?>
				<h1><?php echo esc_html( $hero_heading ); ?></h1>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<?php if ( $intro_image || $intro_heading || $intro_paragraph_one || $intro_paragraph_two ) : ?>
		<section class="about-intro content-shell">
			<?php echo webwise_render_image( $intro_image, 'large', 'about-intro-image' ); ?>
			<div class="about-intro-copy">
				<?php if ( $intro_heading ) : ?>
					<h2><?php echo nl2br( esc_html( $intro_heading ) ); ?></h2>
				<?php endif; ?>
				<?php if ( $intro_paragraph_one ) : ?>
					<p><?php echo nl2br( esc_html( $intro_paragraph_one ) ); ?></p>
				<?php endif; ?>
				<?php if ( $intro_paragraph_two ) : ?>
					<p><?php echo nl2br( esc_html( $intro_paragraph_two ) ); ?></p>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $philosophy_heading || $philosophy_copy || ! empty( $philosophy_cards ) ) : ?>
		<section class="about-philosophy">
			<div class="content-shell">
				<?php if ( $philosophy_heading ) : ?>
					<h2 class="section-title"><?php echo esc_html( $philosophy_heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $philosophy_copy ) : ?>
					<p class="about-section-intro"><?php echo esc_html( $philosophy_copy ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $philosophy_cards ) ) : ?>
					<div class="philosophy-cards">
						<?php foreach ( $philosophy_cards as $card ) : ?>
							<article class="philosophy-card">
								<?php echo webwise_render_image( $card['icon'] ?? 0, 'thumbnail', 'philosophy-card-icon' ); ?>
								<?php if ( ! empty( $card['title'] ) ) : ?>
									<h3><?php echo esc_html( $card['title'] ); ?></h3>
								<?php endif; ?>
								<?php if ( ! empty( $card['copy'] ) ) : ?>
									<p><?php echo esc_html( $card['copy'] ); ?></p>
								<?php endif; ?>
							</article>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $services_intro || $services_heading || ! empty( $services ) ) : ?>
		<section class="about-services content-shell">
			<?php if ( $services_intro ) : ?>
				<p class="section-eyebrow"><?php echo esc_html( $services_intro ); ?></p>
			<?php endif; ?>
			<?php if ( $services_heading ) : ?>
				<h2 class="section-title"><?php echo esc_html( $services_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $services ) ) : ?>
				<div class="about-services-list">
					<?php foreach ( $services as $service ) : ?>
						<article class="about-service-item">
							<div class="about-service-image-wrap">
								<?php echo webwise_render_image( $service['image'] ?? 0, 'large', 'about-service-image' ); ?>
							</div>
							<div class="about-service-copy">
								<?php if ( ! empty( $service['title'] ) ) : ?>
									<h3><?php echo esc_html( $service['title'] ); ?></h3>
								<?php endif; ?>
								<?php if ( ! empty( $service['copy'] ) ) : ?>
									<div class="service-description"><?php echo wp_kses_post( $service['copy'] ); ?></div>
								<?php endif; ?>
								<?php if ( ! empty( $service['cta_label'] ) || ! empty( $service['link'] ) ) : ?>
									<?php echo webwise_render_labeled_link( $service['cta_label'] ?? '', $service['link'] ?? array(), 'text-button' ); ?>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<?php if ( $campaign_image || $campaign_heading ) : ?>
		<section class="about-campaign content-shell">
			<div class="about-campaign-content">
				<?php echo webwise_render_image( $campaign_image, 'full', 'about-campaign-image' ); ?>
				<div class="about-campaign-shade"></div>
				<?php if ( $campaign_heading ) : ?>
					<h2><?php echo nl2br( esc_html( $campaign_heading ) ); ?></h2>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $press_intro || $press_heading || ! empty( $press_logos ) ) : ?>
		<section class="about-press content-shell">
			<?php if ( $press_intro ) : ?>
				<p class="section-eyebrow"><?php echo esc_html( $press_intro ); ?></p>
			<?php endif; ?>
			<?php if ( $press_heading ) : ?>
				<h2 class="section-title"><?php echo esc_html( $press_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $press_logos ) ) : ?>
				<div class="press-logos">
					<?php foreach ( $press_logos as $logo ) : ?>
						<?php
						$logo_image = $logo['image'] ?? 0;
						$logo_link  = $logo['link'] ?? array();
						?>
						<?php if ( ! empty( $logo_link['url'] ) ) : ?>
							<a <?php echo webwise_link_attributes( $logo_link, 'press-logo-link' ); ?>>
						<?php else : ?>
							<div class="press-logo-link">
						<?php endif; ?>
							<?php echo webwise_render_image( $logo_image, 'medium', 'press-logo-image' ); ?>
						<?php if ( ! empty( $logo_link['url'] ) ) : ?>
							</a>
						<?php else : ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<?php if ( $social_heading || ! empty( $social_images ) ) : ?>
		<section class="about-social content-shell">
			<?php if ( $social_heading ) : ?>
				<h2 class="section-title"><?php echo esc_html( $social_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $social_images ) ) : ?>
				<div class="social-gallery">
					<?php foreach ( $social_images as $social_item ) : ?>
						<?php
						$social_image = $social_item['image'] ?? 0;
						$social_link  = $social_item['link'] ?? array();
						?>
						<?php if ( ! empty( $social_link['url'] ) ) : ?>
							<a <?php echo webwise_link_attributes( $social_link, 'social-image-link' ); ?>>
						<?php else : ?>
							<div class="social-image-link">
						<?php endif; ?>
							<?php echo webwise_render_image( $social_image, 'medium_large', 'social-image' ); ?>
						<?php if ( ! empty( $social_link['url'] ) ) : ?>
							</a>
						<?php else : ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</section>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
