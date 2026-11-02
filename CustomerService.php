<?php
/**
 * Template Name: Customer Service
 * Description: Customer service FAQ page managed with ACF.
 */

get_header();

$hero_image   = webwise_get_page_field( 'customer_service_hero_image' );
$hero_heading = webwise_get_page_field( 'customer_service_hero_heading' );
$page_heading = webwise_get_page_field( 'customer_service_heading' );
$categories   = webwise_get_page_field( 'customer_service_categories' );
$faqs         = webwise_get_page_field( 'customer_service_faqs' );

$categories    = is_array( $categories ) ? array_values( $categories ) : array();
$faqs          = is_array( $faqs ) ? array_values( array_filter( $faqs, static function ( $faq ) {
	return is_array( $faq ) && ( ! empty( $faq['question'] ) || ! empty( $faq['answer'] ) );
} ) ) : array();
$active_category = '';
$active_category_label = '';
foreach ( $categories as $category ) {
	if ( ! empty( $category['key'] ) && ! empty( $category['label'] ) ) {
		$active_category       = $category['key'];
		$active_category_label = $category['label'];
		break;
	}
}
?>

<main id="primary" class="site-main customer-service-page">
	<?php if ( $hero_image || $hero_heading ) : ?>
		<section class="customer-service-hero">
			<?php echo webwise_render_image( $hero_image, 'full', 'customer-service-hero-image' ); ?>
			<div class="customer-service-hero-shade"></div>
			<?php if ( $hero_heading ) : ?>
				<h1><?php echo esc_html( $hero_heading ); ?></h1>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<section class="customer-service-content">
		<?php if ( $page_heading ) : ?>
			<h2 class="customer-service-title"><?php echo esc_html( $page_heading ); ?></h2>
		<?php endif; ?>

		<?php if ( ! empty( $categories ) ) : ?>
			<nav class="customer-service-categories" aria-label="<?php esc_attr_e( 'Customer service categories', 'twentytwentyfour-child' ); ?>" role="tablist">
				<?php foreach ( $categories as $index => $category ) : ?>
					<?php
					$category_key   = $category['key'] ?? '';
					$category_label = ! empty( $category['label'] ) ? $category['label'] : '';
					if ( ! $category_key || ! $category_label ) {
						continue;
					}
					?>
					<button
						class="customer-service-category<?php echo $category_key === $active_category ? ' is-active' : ''; ?>"
						type="button"
						id="customer-service-tab-<?php echo esc_attr( $category_key ); ?>"
						role="tab"
						aria-selected="<?php echo $category_key === $active_category ? 'true' : 'false'; ?>"
						aria-controls="customer-service-faqs"
						data-customer-service-category="<?php echo esc_attr( $category_key ); ?>"
					>
						<?php echo webwise_render_image( $category['icon'] ?? 0, 'thumbnail', 'customer-service-category-icon' ); ?>
						<span><?php echo esc_html( $category_label ); ?></span>
					</button>
				<?php endforeach; ?>
			</nav>

			<section class="customer-service-faq-section" id="customer-service-faqs" role="tabpanel" aria-labelledby="customer-service-tab-<?php echo esc_attr( $active_category ); ?>">
				<?php if ( $active_category_label ) : ?>
					<h2 class="customer-service-faq-heading" data-customer-service-heading><?php echo esc_html( $active_category_label ); ?></h2>
				<?php endif; ?>
				<div class="customer-service-faq-list">
					<?php
					$visible_faq_index = 0;
					foreach ( $faqs as $faq ) :
						$faq_category = $faq['category'] ?? '';
						if ( ! $faq_category ) {
							continue;
						}
						$is_active_category = $active_category === $faq_category;
						?>
						<details
							class="customer-service-faq"
							data-customer-service-faq="<?php echo esc_attr( $faq_category ); ?>"
							<?php echo $is_active_category && 0 === $visible_faq_index ? 'open' : ''; ?>
							<?php echo ! $is_active_category ? 'hidden' : ''; ?>
						>
							<summary><?php echo esc_html( $faq['question'] ?? '' ); ?></summary>
							<?php if ( ! empty( $faq['answer'] ) ) : ?>
								<div class="customer-service-faq-answer"><?php echo wp_kses_post( wpautop( $faq['answer'] ) ); ?></div>
							<?php endif; ?>
						</details>
						<?php
						if ( $is_active_category ) {
							++$visible_faq_index;
						}
					endforeach;
					?>
				</div>
			</section>
		<?php endif; ?>
	</section>
</main>

<?php get_footer(); ?>
