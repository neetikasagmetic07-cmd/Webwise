<?php
/**
 * Child theme setup and shared template helpers.
 */

function twentytwentyfour_child_enqueue_styles() {
	$parent_theme = wp_get_theme( get_template() );

	wp_enqueue_style(
		'twentytwentyfour-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		$parent_theme->get( 'Version' )
	);

	wp_enqueue_style(
		'twentytwentyfour-child-style',
		get_stylesheet_uri(),
		array( 'twentytwentyfour-parent-style' ),
		wp_get_theme()->get( 'Version' )
	);

	wp_enqueue_script(
		'webwise-child-theme',
		get_stylesheet_directory_uri() . '/assets/js/theme.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'twentytwentyfour_child_enqueue_styles' );

function webwise_add_product_gallery_support() {
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'webwise_add_product_gallery_support', 100 );

function webwise_enqueue_product_gallery_scripts() {
	if ( ! is_product() ) {
		return;
	}

	wp_enqueue_script( 'wc-flexslider' );
	wp_enqueue_script( 'wc-single-product' );
}
add_action( 'wp_enqueue_scripts', 'webwise_enqueue_product_gallery_scripts', 30 );

function webwise_single_product_carousel_options( $options ) {
	if ( is_product() ) {
		$options['directionNav'] = true;
	}

	return $options;
}
add_filter( 'woocommerce_single_product_carousel_options', 'webwise_single_product_carousel_options' );

function webwise_register_home_page_template( $templates ) {
	if ( file_exists( get_stylesheet_directory() . '/home.php' ) ) {
		$templates['home.php'] = __( 'Home', 'twentytwentyfour-child' );
	}

	if ( file_exists( get_stylesheet_directory() . '/about.php' ) ) {
		$templates['about.php'] = __( 'About', 'twentytwentyfour-child' );
	}

	if ( file_exists( get_stylesheet_directory() . '/CustomerService.php' ) ) {
		$templates['CustomerService.php'] = __( 'Customer Service', 'twentytwentyfour-child' );
	}

	if ( file_exists( get_stylesheet_directory() . '/Privacy policy.php' ) ) {
		$templates['Privacy policy.php'] = __( 'Privacy Policy', 'twentytwentyfour-child' );
	}

	return $templates;
}
add_filter( 'theme_page_templates', 'webwise_register_home_page_template' );

function webwise_register_theme_menus() {
	register_nav_menus(
		array(
			'header-menu-1'  => __( 'Header Menu 1', 'twentytwentyfour-child' ),
			'header-menu-2'  => __( 'Header Menu 2', 'twentytwentyfour-child' ),
			'footer-left'    => __( 'Footer Left Menu', 'twentytwentyfour-child' ),
			'footer-menu-2'  => __( 'Footer Menu 2', 'twentytwentyfour-child' ),
			'footer-menu-3'  => __( 'Footer Menu 3', 'twentytwentyfour-child' ),
		)
	);
}
add_action( 'after_setup_theme', 'webwise_register_theme_menus' );

function webwise_get_field( $field_name, $post_id = 'option' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return false;
	}

	return get_field( $field_name, $post_id );
}

function webwise_get_homepage_field( $field_name ) {
	return webwise_get_page_field( $field_name );
}

function webwise_get_page_field( $field_name ) {
	if ( ! function_exists( 'get_field' ) ) {
		return false;
	}

	return get_field( $field_name, get_queried_object_id() );
}

function webwise_render_image( $image_id, $size = 'large', $class = '' ) {
	if ( ! $image_id || ! is_numeric( $image_id ) ) {
		return '';
	}

	return wp_get_attachment_image(
		(int) $image_id,
		$size,
		false,
		array(
			'class' => $class,
		)
	);
}

function webwise_render_link( $link, $class = '' ) {
	if ( ! is_array( $link ) || empty( $link['url'] ) ) {
		return '';
	}

	$target = ! empty( $link['target'] ) ? $link['target'] : '_self';
	$rel    = '_blank' === $target ? ' rel="noopener noreferrer"' : '';
	$class  = $class ? ' class="' . esc_attr( $class ) . '"' : '';
	$title  = ! empty( $link['title'] ) ? $link['title'] : $link['url'];

	return sprintf(
		'<a href="%1$s" target="%2$s"%3$s%4$s>%5$s</a>',
		esc_url( $link['url'] ),
		esc_attr( $target ),
		$rel,
		$class,
		esc_html( $title )
	);
}

function webwise_render_labeled_link( $label, $link, $class = '' ) {
	$link = is_array( $link ) ? $link : array();

	if ( ! $label && ! empty( $link['title'] ) ) {
		$label = $link['title'];
	}

	if ( ! $label ) {
		return webwise_render_link( $link, $class );
	}

	$class_attribute = $class ? ' class="' . esc_attr( $class ) . '"' : '';

	if ( ! empty( $link['url'] ) ) {
		return sprintf(
			'<a %1$s>%2$s</a>',
			webwise_link_attributes( $link, $class ),
			esc_html( $label )
		);
	}

	return sprintf( '<span%1$s>%2$s</span>', $class_attribute, esc_html( $label ) );
}

function webwise_link_attributes( $link, $class = '' ) {
	if ( ! is_array( $link ) || empty( $link['url'] ) ) {
		return '';
	}

	$target = ! empty( $link['target'] ) ? $link['target'] : '_self';
	$rel    = '_blank' === $target ? ' rel="noopener noreferrer"' : '';

	return sprintf(
		'href="%1$s" target="%2$s"%3$s class="%4$s"',
		esc_url( $link['url'] ),
		esc_attr( $target ),
		$rel,
		esc_attr( $class )
	);
}

function webwise_render_product_rail( $heading, $products, $rail_id ) {
	if ( ! is_array( $products ) || empty( $products ) ) {
		return;
	}

	$products = array_filter(
		$products,
		static function ( $product ) {
			return is_array( $product ) && (
				! empty( $product['image'] )
				|| ! empty( $product['name'] )
				|| ! empty( $product['detail'] )
				|| ! empty( $product['price'] )
			);
		}
	);

	if ( empty( $products ) ) {
		return;
	}
	?>
	<section class="product-section content-shell">
		<div class="section-heading">
			<?php if ( $heading ) : ?>
				<h2><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<div class="rail-controls" aria-label="<?php esc_attr_e( 'Product carousel controls', 'twentytwentyfour-child' ); ?>">
				<button type="button" data-scroll-target="<?php echo esc_attr( $rail_id ); ?>" data-scroll-direction="back" aria-label="<?php esc_attr_e( 'Previous products', 'twentytwentyfour-child' ); ?>">&larr;</button>
				<button type="button" data-scroll-target="<?php echo esc_attr( $rail_id ); ?>" data-scroll-direction="forward" aria-label="<?php esc_attr_e( 'Next products', 'twentytwentyfour-child' ); ?>">&rarr;</button>
			</div>
		</div>
		<div class="product-rail" id="<?php echo esc_attr( $rail_id ); ?>">
			<?php foreach ( $products as $product ) : ?>
				<?php
				$link = ! empty( $product['link'] ) && is_array( $product['link'] ) ? $product['link'] : array();
				?>
				<article class="product-card">
					<?php if ( ! empty( $link['url'] ) ) : ?>
						<a <?php echo webwise_link_attributes( $link, 'product-card-link' ); ?>>
					<?php else : ?>
						<div class="product-card-link">
					<?php endif; ?>
						<div class="product-card-image">
							<?php if ( ! empty( $product['eyebrow'] ) ) : ?>
								<span class="product-eyebrow"><?php echo esc_html( $product['eyebrow'] ); ?></span>
							<?php endif; ?>
							<?php if ( ! empty( $product['badge'] ) ) : ?>
								<span class="product-badge"><?php echo esc_html( $product['badge'] ); ?></span>
							<?php endif; ?>
							<?php echo webwise_render_image( $product['image'] ?? 0, 'medium_large', 'product-image' ); ?>
						</div>
						<div class="product-card-copy">
							<div>
								<?php if ( ! empty( $product['name'] ) ) : ?>
									<h3><?php echo esc_html( $product['name'] ); ?></h3>
								<?php endif; ?>
								<?php if ( ! empty( $product['detail'] ) ) : ?>
									<p><?php echo esc_html( $product['detail'] ); ?></p>
								<?php endif; ?>
							</div>
							<?php if ( ! empty( $product['price'] ) ) : ?>
								<span class="product-price"><?php echo esc_html( $product['price'] ); ?></span>
							<?php endif; ?>
						</div>
					<?php if ( ! empty( $link['url'] ) ) : ?>
						</a>
					<?php else : ?>
						</div>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
}

require_once get_stylesheet_directory() . '/inc/acf-fields.php';

function webwise_use_product_category_php_template( $has_template, $template_name ) {
	if ( in_array( $template_name, array( 'taxonomy-product_cat', 'single-product' ), true ) ) {
		return false;
	}

	return $has_template;
}
add_filter( 'woocommerce_has_block_template', 'webwise_use_product_category_php_template', 20, 2 );

function webwise_configure_single_product_page() {
	if ( ! is_product() ) {
		return;
	}

	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
	add_action( 'woocommerce_single_product_summary', 'webwise_render_single_product_label', 4 );
	add_action( 'woocommerce_single_product_summary', 'webwise_render_single_product_accordions', 35 );

	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	add_action( 'woocommerce_after_single_product_summary', 'webwise_render_single_product_attributes', 5 );
	add_action( 'woocommerce_after_single_product_summary', 'webwise_render_single_product_detail_image', 10 );
	add_action( 'woocommerce_after_single_product_summary', 'webwise_render_product_category_social_gallery', 30 );
}
add_action( 'wp', 'webwise_configure_single_product_page' );

function webwise_render_single_product_label() {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$product_tags = get_the_terms( $product->get_id(), 'product_tag' );

	if ( is_wp_error( $product_tags ) ) {
		error_log( 'WebWise single product labels: ' . $product_tags->get_error_message() );
		return;
	}

	if ( ! $product_tags ) {
		return;
	}

	foreach ( $product_tags as $product_tag ) {
		if ( in_array( strtoupper( $product_tag->name ), array( 'EXCLUSIVE', 'NOVELTY' ), true ) ) {
			printf( '<p class="webwise-single-product-label">%s</p>', esc_html( $product_tag->name ) );
			return;
		}
	}
}

function webwise_render_single_product_accordions() {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$description = $product->get_description();
	$dimensions  = $product->has_dimensions() ? wc_format_dimensions( $product->get_dimensions( false ) ) : '';
	?>
	<div class="webwise-single-product-accordions">
		<?php if ( $description || $product->get_attributes() ) : ?>
			<details class="webwise-single-product-accordion" open>
				<summary><?php esc_html_e( 'Features', 'twentytwentyfour-child' ); ?></summary>
				<div class="webwise-single-product-accordion-content">
					<?php if ( $description ) : ?>
						<?php echo wp_kses_post( wpautop( $description ) ); ?>
					<?php else : ?>
						<?php wc_display_product_attributes( $product ); ?>
					<?php endif; ?>
				</div>
			</details>
		<?php endif; ?>
		<details class="webwise-single-product-accordion">
			<summary><?php esc_html_e( 'Size guide', 'twentytwentyfour-child' ); ?></summary>
			<div class="webwise-single-product-accordion-content">
				<?php if ( $dimensions ) : ?>
					<p><?php echo esc_html( $dimensions ); ?></p>
				<?php else : ?>
					<p><?php esc_html_e( 'Size information is not available for this product.', 'twentytwentyfour-child' ); ?></p>
				<?php endif; ?>
			</div>
		</details>
	</div>
	<?php
}

function webwise_render_single_product_attributes() {
	global $product;

	if ( ! $product instanceof WC_Product || ! $product->get_attributes() ) {
		return;
	}
	?>
	<section class="webwise-single-product-attributes" aria-label="<?php esc_attr_e( 'Product details', 'twentytwentyfour-child' ); ?>">
		<?php wc_display_product_attributes( $product ); ?>
	</section>
	<?php
}

function webwise_render_single_product_detail_image() {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$gallery_image_ids = $product->get_gallery_image_ids();

	if ( count( $gallery_image_ids ) < 3 ) {
		return;
	}

	$image_id = absint( end( $gallery_image_ids ) );

	if ( ! $image_id ) {
		return;
	}
	?>
	<div class="webwise-single-product-detail-image">
		<?php echo wp_get_attachment_image( $image_id, 'large', false, array( 'alt' => $product->get_name() ) ); ?>
	</div>
	<?php
}

function webwise_single_product_add_to_cart_text( $text, $product ) {
	if ( is_product() && $product instanceof WC_Product ) {
		return __( 'Buy now', 'twentytwentyfour-child' );
	}

	return $text;
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'webwise_single_product_add_to_cart_text', 10, 2 );

function webwise_single_product_related_heading( $heading ) {
	return is_product() ? __( 'Suggestions', 'twentytwentyfour-child' ) : $heading;
}
add_filter( 'woocommerce_product_related_products_heading', 'webwise_single_product_related_heading' );

function webwise_single_product_related_args( $args ) {
	if ( is_product() ) {
		$args['posts_per_page'] = 3;
		$args['columns']        = 3;
	}

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'webwise_single_product_related_args' );

function webwise_single_product_price_args( $args ) {
	if ( is_product() ) {
		$args['decimals']     = 0;
		$args['price_format'] = '%2$s %1$s';
	}

	return $args;
}
add_filter( 'wc_price_args', 'webwise_single_product_price_args' );

function webwise_configure_product_category_archive() {
	if ( ! is_product_category() ) {
		return;
	}

	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	remove_action( 'woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10 );
	add_action( 'woocommerce_shop_loop_header', 'webwise_render_product_category_header', 10 );

	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	add_action( 'woocommerce_before_shop_loop', 'webwise_render_product_category_toolbar', 20 );

	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	add_action( 'woocommerce_before_shop_loop_item', 'webwise_render_product_category_badges', 5 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'webwise_render_product_category_product_meta', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
	add_action( 'woocommerce_after_shop_loop', 'webwise_render_product_category_load_more', 10 );
	add_action( 'woocommerce_after_shop_loop', 'webwise_render_product_category_social_gallery', 20 );
}
add_action( 'wp', 'webwise_configure_product_category_archive' );

function webwise_render_product_category_header() {
	$term = get_queried_object();

	if ( ! $term instanceof WP_Term ) {
		return;
	}

	$image_id    = absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
	$description = term_description( $term->term_id, $term->taxonomy );
	?>
	<section class="webwise-product-category-hero" aria-labelledby="webwise-product-category-title">
		<?php
		if ( $image_id ) {
			echo wp_get_attachment_image( $image_id, 'full', false, array( 'class' => 'webwise-product-category-hero-image' ) );
		}
		?>
		<div class="webwise-product-category-hero-shade"></div>
		<h1 id="webwise-product-category-title"><?php echo esc_html( single_term_title( '', false ) ); ?></h1>
	</section>
	<?php if ( $description ) : ?>
		<div class="webwise-product-category-intro">
			<?php echo wp_kses_post( $description ); ?>
		</div>
	<?php endif; ?>
	<?php
}

function webwise_render_product_category_toolbar() {
	$result_count   = absint( wc_get_loop_prop( 'total' ) );
	$selected_color = isset( $_GET['webwise_color'] ) ? array_map( 'sanitize_title', (array) wp_unslash( $_GET['webwise_color'] ) ) : array();
	$colors         = get_terms(
		array(
			'taxonomy'   => 'pa_color',
			'hide_empty' => true,
		)
	);
	$term           = get_queried_object();
	$term_link      = $term instanceof WP_Term ? get_term_link( $term ) : home_url( '/' );

	if ( is_wp_error( $term_link ) ) {
		error_log( 'WebWise product category link: ' . $term_link->get_error_message() );
		$term_link = home_url( '/' );
	}
	?>
	<div class="webwise-product-category-toolbar">
		<details class="webwise-product-filters"<?php echo $selected_color ? ' open' : ''; ?>>
			<summary>
				<svg aria-hidden="true" viewBox="0 0 16 16" focusable="false">
					<path d="M2 3h12M4 8h8m-6 5h4M5 1v4m6-4v4M7 6v4m2 1v4" fill="none" stroke="currentColor" stroke-width="1.2"/>
				</svg>
				<span><?php esc_html_e( 'Filters', 'twentytwentyfour-child' ); ?></span>
			</summary>
			<?php if ( ! is_wp_error( $colors ) && $colors ) : ?>
				<form class="webwise-product-filter-form" method="get" action="<?php echo esc_url( $term_link ); ?>">
					<fieldset>
						<legend><?php esc_html_e( 'Color', 'twentytwentyfour-child' ); ?></legend>
						<?php foreach ( $colors as $color ) : ?>
							<label>
								<input
									type="checkbox"
									name="webwise_color[]"
									value="<?php echo esc_attr( $color->slug ); ?>"
									<?php checked( in_array( $color->slug, $selected_color, true ) ); ?>
								>
								<span><?php echo esc_html( $color->name ); ?></span>
							</label>
						<?php endforeach; ?>
					</fieldset>
					<div class="webwise-product-filter-actions">
						<button type="submit"><?php esc_html_e( 'Apply filters', 'twentytwentyfour-child' ); ?></button>
						<?php if ( $selected_color ) : ?>
							<a href="<?php echo esc_url( $term_link ); ?>"><?php esc_html_e( 'Clear', 'twentytwentyfour-child' ); ?></a>
						<?php endif; ?>
					</div>
				</form>
			<?php elseif ( is_wp_error( $colors ) ) : ?>
				<?php error_log( 'WebWise product category filters: ' . $colors->get_error_message() ); ?>
			<?php endif; ?>
		</details>
		<p class="webwise-product-results-count">
			<?php
			printf(
				esc_html( _n( '%s Result', '%s Results', $result_count, 'twentytwentyfour-child' ) ),
				esc_html( number_format_i18n( $result_count ) )
			);
			?>
		</p>
	</div>
	<?php
}

function webwise_filter_product_category_by_color( $tax_query, $query ) {
	if ( ! is_product_category() || ! $query instanceof WP_Query || empty( $_GET['webwise_color'] ) ) {
		return $tax_query;
	}

	$selected_colors = array_map( 'sanitize_title', (array) wp_unslash( $_GET['webwise_color'] ) );
	$available_colors = get_terms(
		array(
			'taxonomy'   => 'pa_color',
			'fields'     => 'slugs',
			'hide_empty' => false,
		)
	);

	if ( is_wp_error( $available_colors ) ) {
		error_log( 'WebWise product category color filter: ' . $available_colors->get_error_message() );
		return $tax_query;
	}

	$selected_colors = array_values( array_intersect( $selected_colors, $available_colors ) );

	if ( $selected_colors ) {
		$tax_query[] = array(
			'taxonomy' => 'pa_color',
			'field'    => 'slug',
			'terms'    => $selected_colors,
		);
	}

	return $tax_query;
}
add_filter( 'woocommerce_product_query_tax_query', 'webwise_filter_product_category_by_color', 10, 2 );

function webwise_render_product_category_badges() {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$product_tags = get_the_terms( $product->get_id(), 'product_tag' );

	if ( is_wp_error( $product_tags ) ) {
		error_log( 'WebWise product badge tags: ' . $product_tags->get_error_message() );
		return;
	}

	if ( ! $product_tags ) {
		return;
	}

	$left_badges  = array();
	$right_badges = array();

	foreach ( $product_tags as $product_tag ) {
		if ( in_array( strtoupper( $product_tag->name ), array( 'EXCLUSIVE', 'NOVELTY' ), true ) ) {
			$left_badges[] = $product_tag;
		} else {
			$right_badges[] = $product_tag;
		}
	}
	?>
	<div class="webwise-product-badges" aria-label="<?php esc_attr_e( 'Product labels', 'twentytwentyfour-child' ); ?>">
		<div class="webwise-product-badges-left">
			<?php foreach ( $left_badges as $badge ) : ?>
				<span><?php echo esc_html( $badge->name ); ?></span>
			<?php endforeach; ?>
		</div>
		<div class="webwise-product-badges-right">
			<?php foreach ( $right_badges as $badge ) : ?>
				<span class="<?php echo 0 === strpos( $badge->name, '-' ) ? 'is-discount' : ''; ?>">
					<?php echo esc_html( $badge->name ); ?>
				</span>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

function webwise_render_product_category_product_meta() {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$color_options = $product->get_attribute( 'pa_color' );
	$color_count   = $color_options ? count( array_filter( array_map( 'trim', explode( ',', $color_options ) ) ) ) : 0;
	$price         = $product->get_price();
	?>
	<div class="webwise-product-meta">
		<?php if ( $color_count ) : ?>
			<span class="webwise-product-color-count">
				<?php
				printf(
					esc_html( _n( '%s color', '%s colors', $color_count, 'twentytwentyfour-child' ) ),
					esc_html( number_format_i18n( $color_count ) )
				);
				?>
			</span>
		<?php endif; ?>
		<?php if ( '' !== $price ) : ?>
			<span class="webwise-product-card-price">
				<?php echo wp_kses_post( wc_price( wc_get_price_to_display( $product ), array( 'decimals' => 0, 'price_format' => '%1$s %2$s' ) ) ); ?>
			</span>
		<?php endif; ?>
	</div>
	<?php
}

function webwise_render_product_category_load_more() {
	$total_pages  = absint( wc_get_loop_prop( 'total_pages' ) );
	$current_page = max( 1, absint( wc_get_loop_prop( 'current_page' ) ) );

	if ( $total_pages <= $current_page ) {
		return;
	}
	?>
	<nav class="webwise-product-pagination" aria-label="<?php esc_attr_e( 'Product pagination', 'twentytwentyfour-child' ); ?>">
		<a href="<?php echo esc_url( get_pagenum_link( $current_page + 1 ) ); ?>">
			<?php esc_html_e( 'Load more', 'twentytwentyfour-child' ); ?>
		</a>
	</nav>
	<?php
}

function webwise_render_product_category_social_gallery() {
	$front_page_id = absint( get_option( 'page_on_front' ) );

	if ( ! $front_page_id || ! function_exists( 'get_field' ) ) {
		return;
	}

	$heading      = get_field( 'social_heading', $front_page_id );
	$social_images = get_field( 'social_images', $front_page_id );

	if ( ! $heading && ! $social_images ) {
		return;
	}
	?>
	<section class="social-section content-shell webwise-category-social">
		<?php if ( $heading ) : ?>
			<h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<?php if ( is_array( $social_images ) && $social_images ) : ?>
			<div class="social-gallery">
				<?php foreach ( $social_images as $social_item ) : ?>
					<?php
					$image_id = $social_item['image'] ?? 0;
					$link     = $social_item['link'] ?? array();

					if ( ! $image_id ) {
						continue;
					}
					?>
					<?php if ( ! empty( $link['url'] ) ) : ?>
						<a <?php echo webwise_link_attributes( $link, 'social-image-link' ); ?>>
							<?php echo webwise_render_image( $image_id, 'medium_large', 'social-image' ); ?>
						</a>
					<?php else : ?>
						<div class="social-image-link">
							<?php echo webwise_render_image( $image_id, 'medium_large', 'social-image' ); ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</section>
	<?php
}

function webwise_product_category_per_page( $limit, $query = null ) {
	if (
		! is_admin()
		&& is_product_category()
		&& ( ! ( $query instanceof WP_Query ) || $query->is_main_query() )
	) {
		return 15;
	}

	return $limit;
}
add_filter( 'loop_shop_per_page', 'webwise_product_category_per_page', 20, 2 );
