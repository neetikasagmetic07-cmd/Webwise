<?php
/**
 * Template Name: Home
 * Description: Figma-inspired WebWise homepage.
 */

get_header();

$hero_image             = webwise_get_homepage_field( 'hero_image' );
$hero_heading            = webwise_get_homepage_field( 'hero_heading' );
$hero_supporting_copy    = webwise_get_homepage_field( 'hero_supporting_copy' );
$best_sellers_heading    = webwise_get_homepage_field( 'best_sellers_heading' );
$best_sellers_products   = webwise_get_homepage_field( 'best_sellers_products' );
$featured_product_image  = webwise_get_homepage_field( 'featured_product_image' );
$featured_product_name   = webwise_get_homepage_field( 'featured_product_name' );
$featured_product_detail = webwise_get_homepage_field( 'featured_product_detail' );
$featured_product_price  = webwise_get_homepage_field( 'featured_product_price' );
$featured_product_cta    = webwise_get_homepage_field( 'featured_product_cta' );
$featured_product_link   = webwise_get_homepage_field( 'featured_product_link' );
$campaign_tiles          = webwise_get_homepage_field( 'campaign_tiles' );
$editorial_feature_image = webwise_get_homepage_field( 'editorial_feature_image' );
$editorial_feature_title = webwise_get_homepage_field( 'editorial_feature_heading' );
$editorial_feature_copy  = webwise_get_homepage_field( 'editorial_feature_copy' );
$editorial_feature_link  = webwise_get_homepage_field( 'editorial_feature_link' );
$editorial_image         = webwise_get_homepage_field( 'editorial_image' );
$must_haves_heading      = webwise_get_homepage_field( 'must_haves_heading' );
$must_haves_products     = webwise_get_homepage_field( 'must_haves_products' );
$brand_statement         = webwise_get_homepage_field( 'brand_statement' );
$services_intro          = webwise_get_homepage_field( 'services_intro' );
$services_heading        = webwise_get_homepage_field( 'services_heading' );
$services                = webwise_get_homepage_field( 'services' );
$campaign_banner_image   = webwise_get_homepage_field( 'campaign_banner_image' );
$campaign_banner_heading = webwise_get_homepage_field( 'campaign_banner_heading' );
$campaign_banner_copy    = webwise_get_homepage_field( 'campaign_banner_copy' );
$campaign_banner_link    = webwise_get_homepage_field( 'campaign_banner_link' );
$press_intro             = webwise_get_homepage_field( 'press_intro' );
$press_heading           = webwise_get_homepage_field( 'press_heading' );
$press_logos             = webwise_get_homepage_field( 'press_logos' );
$social_heading          = webwise_get_homepage_field( 'social_heading' );
$social_images           = webwise_get_homepage_field( 'social_images' );

$campaign_tiles = is_array( $campaign_tiles ) ? array_values( array_filter( $campaign_tiles, static function ( $item ) {
	return is_array( $item ) && ( ! empty( $item['image'] ) || ! empty( $item['title'] ) );
} ) ) : array();
$services = is_array( $services ) ? array_values( array_filter( $services, static function ( $item ) {
	return is_array( $item ) && (
		! empty( $item['image'] )
		|| ! empty( $item['title'] )
		|| ! empty( $item['copy'] )
		|| ! empty( $item['link'] )
	);
} ) ) : array();
$press_logos = is_array( $press_logos ) ? array_values( array_filter( $press_logos, static function ( $item ) {
	return is_array( $item ) && ( ! empty( $item['image'] ) || ! empty( $item['link'] ) );
} ) ) : array();
$social_images = is_array( $social_images ) ? array_values( array_filter( $social_images, static function ( $item ) {
	return is_array( $item ) && ! empty( $item['image'] );
} ) ) : array();
?>

<main id="primary" class="site-main">
	<?php if ( $hero_image || $hero_heading || $hero_supporting_copy ) : ?>
		<section class="hero-section">
			<?php echo webwise_render_image( $hero_image, 'full', 'hero-image' ); ?>
			<div class="hero-shade"></div>
			<div class="hero-copy">
				<?php if ( $hero_heading ) : ?>
					<h1><?php echo esc_html( $hero_heading ); ?></h1>
				<?php endif; ?>
				<?php if ( $hero_supporting_copy ) : ?>
					<p class="hero-supporting-copy"><?php echo nl2br( esc_html( $hero_supporting_copy ) ); ?></p>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php webwise_render_product_rail( $best_sellers_heading, $best_sellers_products, 'best-sellers-rail' ); ?>

	<?php if ( $featured_product_image || $featured_product_name || $featured_product_detail || $featured_product_price ) : ?>
		<section class="featured-product-section">
			<?php if ( ! empty( $featured_product_link['url'] ) ) : ?>
				<a <?php echo webwise_link_attributes( $featured_product_link, 'featured-product-link' ); ?>>
			<?php else : ?>
				<div class="featured-product-link">
			<?php endif; ?>
				<?php echo webwise_render_image( $featured_product_image, 'large', 'featured-product-image' ); ?>
				<div class="featured-product-copy">
					<?php if ( $featured_product_name ) : ?>
						<h2><?php echo esc_html( $featured_product_name ); ?></h2>
					<?php endif; ?>
					<?php if ( $featured_product_detail ) : ?>
						<p><?php echo nl2br( esc_html( $featured_product_detail ) ); ?></p>
					<?php endif; ?>
					<?php if ( $featured_product_price ) : ?>
						<p class="featured-product-price"><?php echo esc_html( $featured_product_price ); ?></p>
					<?php endif; ?>
					<?php if ( $featured_product_cta ) : ?>
						<span class="text-button"><?php echo esc_html( $featured_product_cta ); ?></span>
					<?php endif; ?>
				</div>
			<?php if ( ! empty( $featured_product_link['url'] ) ) : ?>
				</a>
			<?php else : ?>
				</div>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<?php if ( is_array( $campaign_tiles ) && ! empty( $campaign_tiles ) ) : ?>
		<section class="campaign-grid content-shell" aria-label="<?php esc_attr_e( 'Campaigns', 'twentytwentyfour-child' ); ?>">
			<?php foreach ( $campaign_tiles as $campaign_tile ) : ?>
				<?php
				$tile_image = $campaign_tile['image'] ?? 0;
				$tile_title = $campaign_tile['title'] ?? '';
				$tile_link  = $campaign_tile['link'] ?? array();
				?>
				<article class="campaign-tile">
					<?php if ( ! empty( $tile_link['url'] ) ) : ?>
						<a <?php echo webwise_link_attributes( $tile_link, 'campaign-tile-link' ); ?>>
					<?php else : ?>
						<div class="campaign-tile-link">
					<?php endif; ?>
						<?php echo webwise_render_image( $tile_image, 'large', 'campaign-tile-image' ); ?>
						<?php if ( $tile_title ) : ?>
							<h2><?php echo esc_html( $tile_title ); ?></h2>
						<?php endif; ?>
					<?php if ( ! empty( $tile_link['url'] ) ) : ?>
						</a>
					<?php else : ?>
						</div>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</section>
	<?php endif; ?>

	<?php if ( $editorial_feature_image || $editorial_feature_title || $editorial_feature_copy ) : ?>
		<section class="editorial-feature">
			<?php if ( ! empty( $editorial_feature_link['url'] ) ) : ?>
				<a <?php echo webwise_link_attributes( $editorial_feature_link, 'editorial-feature-link' ); ?>>
			<?php else : ?>
				<div class="editorial-feature-link">
			<?php endif; ?>
				<?php echo webwise_render_image( $editorial_feature_image, 'full', 'editorial-feature-image' ); ?>
				<div class="editorial-feature-shade"></div>
				<div class="editorial-feature-copy">
					<?php if ( $editorial_feature_title ) : ?>
						<h2><?php echo esc_html( $editorial_feature_title ); ?></h2>
					<?php endif; ?>
					<?php if ( $editorial_feature_copy ) : ?>
						<p><?php echo nl2br( esc_html( $editorial_feature_copy ) ); ?></p>
					<?php endif; ?>
				</div>
			<?php if ( ! empty( $editorial_feature_link['url'] ) ) : ?>
				</a>
			<?php else : ?>
				</div>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<?php if ( $editorial_image ) : ?>
		<section class="editorial-image-section">
			<?php echo webwise_render_image( $editorial_image, 'full', 'editorial-image' ); ?>
		</section>
	<?php endif; ?>

	<?php webwise_render_product_rail( $must_haves_heading, $must_haves_products, 'must-haves-rail' ); ?>

	<?php if ( $brand_statement ) : ?>
		<section class="brand-statement content-shell">
			<p><?php echo nl2br( esc_html( $brand_statement ) ); ?></p>
		</section>
	<?php endif; ?>

	<?php if ( ( $services_intro || $services_heading || is_array( $services ) ) && ! empty( $services ) ) : ?>
		<section class="services-section content-shell">
			<?php if ( $services_intro ) : ?>
				<p class="section-eyebrow"><?php echo esc_html( $services_intro ); ?></p>
			<?php endif; ?>
			<?php if ( $services_heading ) : ?>
				<h2 class="section-title"><?php echo esc_html( $services_heading ); ?></h2>
			<?php endif; ?>
			<div class="services-list">
				<?php foreach ( $services as $service ) : ?>
					<?php
					$service_image = $service['image'] ?? 0;
					$service_title = $service['title'] ?? '';
					$service_copy  = $service['copy'] ?? '';
					$service_cta_label = $service['cta_label'] ?? '';
					$service_link  = $service['link'] ?? array();
					?>
					<article class="service-item">
						<div class="service-images">
							<?php echo webwise_render_image( $service_image, 'large', 'service-image' ); ?>
						</div>
						<div class="service-copy">
							<?php if ( $service_title ) : ?>
								<h3><?php echo esc_html( $service_title ); ?></h3>
							<?php endif; ?>
							<?php if ( $service_copy ) : ?>
								<div class="service-description"><?php echo wp_kses_post( $service_copy ); ?></div>
							<?php endif; ?>
							<?php if ( $service_cta_label || ! empty( $service_link ) ) : ?>
								<?php echo webwise_render_labeled_link( $service_cta_label, $service_link, 'text-button' ); ?>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $campaign_banner_image || $campaign_banner_heading || $campaign_banner_copy ) : ?>
		<section class="campaign-banner content-shell">
			<?php if ( ! empty( $campaign_banner_link['url'] ) ) : ?>
				<a <?php echo webwise_link_attributes( $campaign_banner_link, 'campaign-banner-link' ); ?>>
			<?php else : ?>
				<div class="campaign-banner-link">
			<?php endif; ?>
				<?php echo webwise_render_image( $campaign_banner_image, 'full', 'campaign-banner-image' ); ?>
				<div class="campaign-banner-shade"></div>
				<div class="campaign-banner-copy">
					<?php if ( $campaign_banner_heading ) : ?>
						<h2><?php echo esc_html( $campaign_banner_heading ); ?></h2>
					<?php endif; ?>
					<?php if ( $campaign_banner_copy ) : ?>
						<p><?php echo nl2br( esc_html( $campaign_banner_copy ) ); ?></p>
					<?php endif; ?>
				</div>
			<?php if ( ! empty( $campaign_banner_link['url'] ) ) : ?>
				</a>
			<?php else : ?>
				</div>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<?php if ( $press_intro || $press_heading || ( is_array( $press_logos ) && ! empty( $press_logos ) ) ) : ?>
		<section class="press-section content-shell">
			<?php if ( $press_intro ) : ?>
				<p class="section-eyebrow"><?php echo esc_html( $press_intro ); ?></p>
			<?php endif; ?>
			<?php if ( $press_heading ) : ?>
				<h2 class="section-title"><?php echo esc_html( $press_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( is_array( $press_logos ) && ! empty( $press_logos ) ) : ?>
				<div class="press-logos">
				<?php foreach ( $press_logos as $press_logo ) : ?>
					<?php
					$press_image = $press_logo['image'] ?? 0;
					$press_link  = $press_logo['link'] ?? array();
					?>
					<?php if ( ! empty( $press_link['url'] ) ) : ?>
						<a <?php echo webwise_link_attributes( $press_link, 'press-logo-link' ); ?>>
					<?php else : ?>
						<div class="press-logo-link">
					<?php endif; ?>
						<?php echo webwise_render_image( $press_image, 'medium', 'press-logo-image' ); ?>
					<?php if ( ! empty( $press_link['url'] ) ) : ?>
						</a>
					<?php else : ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<?php if ( $social_heading || ( is_array( $social_images ) && ! empty( $social_images ) ) ) : ?>
		<section class="social-section content-shell">
			<?php if ( $social_heading ) : ?>
				<h2 class="section-title"><?php echo esc_html( $social_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( is_array( $social_images ) && ! empty( $social_images ) ) : ?>
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
