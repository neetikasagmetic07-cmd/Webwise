<?php
/**
 * Template Name: Privacy Policy
 * Description: Privacy policy page with ACF-managed sections.
 */

get_header();

$hero_image      = webwise_get_page_field( 'privacy_policy_hero_image' );
$hero_heading    = webwise_get_page_field( 'privacy_policy_hero_heading' );
$policy_heading  = webwise_get_page_field( 'privacy_policy_heading' );
$last_updated    = webwise_get_page_field( 'privacy_policy_updated' );
$sidebar_links   = webwise_get_page_field( 'privacy_policy_sidebar_links' );
$policy_sections = webwise_get_page_field( 'privacy_policy_sections' );

$sidebar_links   = is_array( $sidebar_links ) ? array_values( array_filter( $sidebar_links, static function ( $link ) {
	return is_array( $link ) && ! empty( $link['label'] );
} ) ) : array();
$policy_sections = is_array( $policy_sections ) ? array_values( array_filter( $policy_sections, static function ( $section ) {
	return is_array( $section ) && ! empty( $section['heading'] );
} ) ) : array();
?>

<main id="primary" class="site-main privacy-policy-page">
	<?php if ( $hero_image || $hero_heading ) : ?>
		<section class="privacy-policy-hero">
			<?php echo webwise_render_image( $hero_image, 'full', 'privacy-policy-hero-image' ); ?>
			<div class="privacy-policy-hero-shade"></div>
			<?php if ( $hero_heading ) : ?>
				<h1><?php echo esc_html( $hero_heading ); ?></h1>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<div class="privacy-policy-layout">
		<?php if ( ! empty( $sidebar_links ) ) : ?>
			<nav class="privacy-policy-sidebar" aria-label="<?php esc_attr_e( 'Policy navigation', 'twentytwentyfour-child' ); ?>">
				<ul>
					<?php foreach ( $sidebar_links as $index => $item ) : ?>
						<?php
						$link  = ! empty( $item['link'] ) && is_array( $item['link'] ) ? $item['link'] : array();
						$label = $item['label'];
						?>
						<li>
							<?php if ( ! empty( $link['url'] ) ) : ?>
								<a href="<?php echo esc_url( $link['url'] ); ?>"<?php echo 2 === $index ? ' class="is-current" aria-current="page"' : ''; ?><?php echo ! empty( $link['target'] ) && '_blank' === $link['target'] ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $label ); ?></a>
							<?php else : ?>
								<span<?php echo 2 === $index ? ' class="is-current" aria-current="page"' : ''; ?>><?php echo esc_html( $label ); ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endif; ?>

		<article class="privacy-policy-content">
			<?php if ( $policy_heading ) : ?>
				<h2><?php echo esc_html( $policy_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $last_updated ) : ?>
				<p class="privacy-policy-updated"><?php echo esc_html( $last_updated ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $policy_sections ) ) : ?>
				<ol class="privacy-policy-toc">
					<?php foreach ( $policy_sections as $section ) : ?>
						<?php $section_id = sanitize_title( $section['heading'] ); ?>
						<li><a href="#<?php echo esc_attr( $section_id ); ?>"><?php echo esc_html( $section['heading'] ); ?></a></li>
					<?php endforeach; ?>
				</ol>

				<div class="privacy-policy-sections">
					<?php foreach ( $policy_sections as $section ) : ?>
						<?php $section_id = sanitize_title( $section['heading'] ); ?>
						<section class="privacy-policy-section" id="<?php echo esc_attr( $section_id ); ?>">
							<h3><?php echo esc_html( $section['heading'] ); ?></h3>
							<?php if ( ! empty( $section['content'] ) ) : ?>
								<div class="privacy-policy-section-content"><?php echo wp_kses_post( $section['content'] ); ?></div>
							<?php endif; ?>
						</section>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</article>
	</div>
</main>

<?php get_footer(); ?>
