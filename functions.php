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
