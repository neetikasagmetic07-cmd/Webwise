<?php
/**
 * ACF field groups for the homepage content managed by this child theme.
 */

function webwise_acf_field( $key, $label, $name, $type = 'text', $settings = array() ) {
	return array_merge(
		array(
			'key'   => 'field_webwise_' . $key,
			'label' => $label,
			'name'  => $name,
			'type'  => $type,
		),
		$settings
	);
}

function webwise_acf_product_fields( $section ) {
	return array(
		webwise_acf_field(
			$section . '_image',
			'Product image',
			'image',
			'image',
			array(
				'return_format' => 'id',
				'preview_size'  => 'medium',
				'library'       => 'all',
			)
		),
		webwise_acf_field( $section . '_eyebrow', 'Eyebrow', 'eyebrow' ),
		webwise_acf_field( $section . '_name', 'Product name', 'name' ),
		webwise_acf_field( $section . '_detail', 'Product detail', 'detail' ),
		webwise_acf_field( $section . '_price', 'Price', 'price' ),
		webwise_acf_field( $section . '_badge', 'Badge', 'badge' ),
		webwise_acf_field(
			$section . '_link',
			'Product link',
			'link',
			'link',
			array( 'return_format' => 'array' )
		),
	);
}

function webwise_register_homepage_acf_fields() {
	if (
		! function_exists( 'acf_add_local_field_group' )
		|| ! function_exists( 'acf_add_options_page' )
		|| ! function_exists( 'acf_get_field_type' )
		|| ! acf_get_field_type( 'repeater' )
	) {
		return;
	}

	static $options_page_registered = false;

	if ( ! $options_page_registered ) {
		acf_add_options_page(
			array(
				'page_title' => __( 'WebWise Homepage Content', 'twentytwentyfour-child' ),
				'menu_title' => __( 'WebWise Homepage', 'twentytwentyfour-child' ),
				'menu_slug'  => 'webwise-homepage-settings',
				'capability' => 'edit_pages',
				'redirect'   => false,
			)
		);
		$options_page_registered = true;
	}

	$register_group = static function ( $group ) {
		$existing_groups = function_exists( 'acf_get_field_groups' ) ? acf_get_field_groups() : array();

		foreach ( $existing_groups as $existing_group ) {
			if (
				( isset( $existing_group['key'] ) && $group['key'] === $existing_group['key'] )
				|| ( isset( $existing_group['title'] ) && $group['title'] === $existing_group['title'] )
			) {
				return;
			}
		}

		acf_add_local_field_group( $group );
	};

	$options_location = array(
		array(
			array(
				'param'    => 'options_page',
				'operator' => '==',
				'value'    => 'webwise-homepage-settings',
			),
		),
	);
	$homepage_location = array(
		array(
			array(
				'param'    => 'page_template',
				'operator' => '==',
				'value'    => 'home.php',
			),
		),
	);
	$about_location = array(
		array(
			array(
				'param'    => 'page_template',
				'operator' => '==',
				'value'    => 'about.php',
			),
		),
	);
	$customer_service_location = array(
		array(
			array(
				'param'    => 'page_template',
				'operator' => '==',
				'value'    => 'CustomerService.php',
			),
		),
	);
	$privacy_policy_location = array(
		array(
			array(
				'param'    => 'page_template',
				'operator' => '==',
				'value'    => 'Privacy policy.php',
			),
		),
	);

	$register_group(
		array(
			'key'                   => 'group_webwise_header',
			'title'                 => 'WebWise Header',
			'fields'                => array(
				webwise_acf_field( 'tab_header_brand', 'Brand', 'header_brand_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'header_logo', 'Logo image', 'header_logo', 'image', array( 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all' ) ),
				webwise_acf_field( 'header_logo_text', 'Logo text fallback', 'header_logo_text' ),
				webwise_acf_field( 'tab_header_navigation', 'Navigation', 'header_navigation_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'header_navigation', 'Navigation links', 'header_navigation', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add navigation link',
					'sub_fields'   => array(
						webwise_acf_field( 'header_nav_label', 'Label', 'label' ),
						webwise_acf_field( 'header_nav_link', 'Link', 'link', 'link', array( 'return_format' => 'array' ) ),
					),
				) ),
				webwise_acf_field( 'tab_header_utilities', 'Utilities and button', 'header_utilities_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'header_actions', 'Utility links', 'header_actions', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add utility link',
					'sub_fields'   => array(
						webwise_acf_field( 'header_action_label', 'Label', 'label' ),
						webwise_acf_field( 'header_action_link', 'Link', 'link', 'link', array( 'return_format' => 'array' ) ),
					),
				) ),
				webwise_acf_field( 'header_button', 'Header button', 'header_button', 'link', array( 'return_format' => 'array' ) ),
			),
			'location'              => $options_location,
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
		)
	);

	$register_group(
		array(
			'key'                   => 'group_webwise_homepage',
			'title'                 => 'WebWise Homepage Sections',
			'fields'                => array(
				webwise_acf_field( 'tab_home_hero', 'Hero', 'home_hero_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'hero_image', 'Hero image', 'hero_image', 'image', array( 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all' ) ),
				webwise_acf_field( 'hero_heading', 'Hero heading', 'hero_heading' ),
				webwise_acf_field( 'hero_supporting_copy', 'Supporting copy', 'hero_supporting_copy', 'textarea', array( 'rows' => 3 ) ),
				webwise_acf_field( 'tab_best_sellers', 'Best sellers', 'best_sellers_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'best_sellers_heading', 'Best sellers heading', 'best_sellers_heading' ),
				webwise_acf_field( 'best_sellers_products', 'Best sellers products', 'best_sellers_products', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add product',
					'collapsed'    => 'field_webwise_best_seller_name',
					'sub_fields'   => webwise_acf_product_fields( 'best_seller' ),
				) ),
				webwise_acf_field( 'tab_featured_product', 'Featured product', 'featured_product_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'featured_product_image', 'Featured product image', 'featured_product_image', 'image', array( 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all' ) ),
				webwise_acf_field( 'featured_product_name', 'Featured product name', 'featured_product_name' ),
				webwise_acf_field( 'featured_product_detail', 'Featured product detail', 'featured_product_detail' ),
				webwise_acf_field( 'featured_product_price', 'Featured product price', 'featured_product_price' ),
				webwise_acf_field( 'featured_product_cta', 'Button label', 'featured_product_cta' ),
				webwise_acf_field( 'featured_product_link', 'Featured product link', 'featured_product_link', 'link', array( 'return_format' => 'array' ) ),
				webwise_acf_field( 'tab_campaign_tiles', 'Campaign tiles', 'campaign_tiles_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'campaign_tiles', 'Campaign tiles', 'campaign_tiles', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add campaign tile',
					'sub_fields'   => array(
						webwise_acf_field( 'campaign_tile_image', 'Image', 'image', 'image', array( 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all' ) ),
						webwise_acf_field( 'campaign_tile_title', 'Title', 'title' ),
						webwise_acf_field( 'campaign_tile_link', 'Link', 'link', 'link', array( 'return_format' => 'array' ) ),
					),
				) ),
				webwise_acf_field( 'tab_editorial_feature', 'Editorial feature', 'editorial_feature_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'editorial_feature_image', 'Editorial feature image', 'editorial_feature_image', 'image', array( 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all' ) ),
				webwise_acf_field( 'editorial_feature_heading', 'Editorial feature heading', 'editorial_feature_heading' ),
				webwise_acf_field( 'editorial_feature_copy', 'Editorial feature copy', 'editorial_feature_copy', 'textarea', array( 'rows' => 3 ) ),
				webwise_acf_field( 'editorial_feature_link', 'Editorial feature link', 'editorial_feature_link', 'link', array( 'return_format' => 'array' ) ),
				webwise_acf_field( 'tab_editorial_image', 'Editorial image', 'editorial_image_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'editorial_image', 'Editorial image', 'editorial_image', 'image', array( 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all' ) ),
				webwise_acf_field( 'tab_must_haves', 'Must haves', 'must_haves_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'must_haves_heading', 'Must haves heading', 'must_haves_heading' ),
				webwise_acf_field( 'must_haves_products', 'Must haves products', 'must_haves_products', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add product',
					'collapsed'    => 'field_webwise_must_have_name',
					'sub_fields'   => webwise_acf_product_fields( 'must_have' ),
				) ),
				webwise_acf_field( 'tab_brand_statement', 'Brand statement', 'brand_statement_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'brand_statement', 'Brand statement', 'brand_statement', 'textarea', array( 'rows' => 3 ) ),
				webwise_acf_field( 'tab_services', 'Services', 'services_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'services_intro', 'Introductory text', 'services_intro' ),
				webwise_acf_field( 'services_heading', 'Services heading', 'services_heading' ),
				webwise_acf_field( 'services', 'Services', 'services', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add service',
					'sub_fields'   => array(
						webwise_acf_field( 'service_image', 'Image', 'image', 'image', array( 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all' ) ),
						webwise_acf_field( 'service_title', 'Title', 'title' ),
						webwise_acf_field( 'service_copy', 'Description', 'copy', 'wysiwyg', array( 'tabs' => 'visual', 'toolbar' => 'basic', 'media_upload' => 0 ) ),
						webwise_acf_field( 'service_cta_label', 'Button label', 'cta_label' ),
						webwise_acf_field( 'service_link', 'Button or link', 'link', 'link', array( 'return_format' => 'array' ) ),
					),
				) ),
				webwise_acf_field( 'tab_campaign_banner', 'Campaign banner', 'campaign_banner_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'campaign_banner_image', 'Campaign banner image', 'campaign_banner_image', 'image', array( 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all' ) ),
				webwise_acf_field( 'campaign_banner_heading', 'Campaign banner heading', 'campaign_banner_heading' ),
				webwise_acf_field( 'campaign_banner_copy', 'Campaign banner copy', 'campaign_banner_copy', 'textarea', array( 'rows' => 3 ) ),
				webwise_acf_field( 'campaign_banner_link', 'Campaign banner link', 'campaign_banner_link', 'link', array( 'return_format' => 'array' ) ),
				webwise_acf_field( 'tab_press', 'Press logos', 'press_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'press_intro', 'Introductory text', 'press_intro' ),
				webwise_acf_field( 'press_heading', 'Press heading', 'press_heading' ),
				webwise_acf_field( 'press_logos', 'Press logos', 'press_logos', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add publication logo',
					'sub_fields'   => array(
						webwise_acf_field( 'press_logo_image', 'Logo image', 'image', 'image', array( 'return_format' => 'id', 'preview_size' => 'thumbnail', 'library' => 'all' ) ),
						webwise_acf_field( 'press_logo_link', 'Link', 'link', 'link', array( 'return_format' => 'array' ) ),
					),
				) ),
				webwise_acf_field( 'tab_social', 'Social gallery', 'social_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'social_heading', 'Social heading', 'social_heading' ),
				webwise_acf_field( 'social_images', 'Social images', 'social_images', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add social image',
					'sub_fields'   => array(
						webwise_acf_field( 'social_image', 'Image', 'image', 'image', array( 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all' ) ),
						webwise_acf_field( 'social_link', 'Link', 'link', 'link', array( 'return_format' => 'array' ) ),
					),
				) ),
			),
			'location'              => $homepage_location,
			'menu_order'            => 1,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
		)
	);

	$register_group(
		array(
			'key'                   => 'group_webwise_about',
			'title'                 => 'WebWise About Page Sections',
			'fields'                => array(
				webwise_acf_field( 'tab_about_hero', 'Hero', 'about_hero_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'about_hero_image', 'Hero image', 'about_hero_image', 'image', array( 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all' ) ),
				webwise_acf_field( 'about_hero_heading', 'Hero heading', 'about_hero_heading' ),
				webwise_acf_field( 'tab_about_intro', 'Welcome section', 'about_intro_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'about_intro_image', 'Image', 'about_intro_image', 'image', array( 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all' ) ),
				webwise_acf_field( 'about_intro_heading', 'Heading', 'about_intro_heading' ),
				webwise_acf_field( 'about_intro_paragraph_one', 'First paragraph', 'about_intro_paragraph_one', 'textarea', array( 'rows' => 4 ) ),
				webwise_acf_field( 'about_intro_paragraph_two', 'Second paragraph', 'about_intro_paragraph_two', 'textarea', array( 'rows' => 3 ) ),
				webwise_acf_field( 'tab_about_philosophy', 'Our Philosophy', 'about_philosophy_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'about_philosophy_heading', 'Heading', 'about_philosophy_heading' ),
				webwise_acf_field( 'about_philosophy_copy', 'Supporting text', 'about_philosophy_copy' ),
				webwise_acf_field( 'about_philosophy_cards', 'Philosophy cards', 'about_philosophy_cards', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add philosophy card',
					'sub_fields'   => array(
						webwise_acf_field( 'about_philosophy_icon', 'Icon image', 'icon', 'image', array( 'return_format' => 'id', 'preview_size' => 'thumbnail', 'library' => 'all' ) ),
						webwise_acf_field( 'about_philosophy_card_title', 'Title', 'title' ),
						webwise_acf_field( 'about_philosophy_card_copy', 'Description', 'copy', 'textarea', array( 'rows' => 3 ) ),
					),
				) ),
				webwise_acf_field( 'tab_about_services', 'Our Services', 'about_services_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'about_services_intro', 'Introductory text', 'about_services_intro' ),
				webwise_acf_field( 'about_services_heading', 'Heading', 'about_services_heading' ),
				webwise_acf_field( 'about_services', 'Services', 'about_services', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add service',
					'sub_fields'   => array(
						webwise_acf_field( 'about_service_image', 'Image', 'image', 'image', array( 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all' ) ),
						webwise_acf_field( 'about_service_title', 'Title', 'title' ),
						webwise_acf_field( 'about_service_copy', 'Description', 'copy', 'wysiwyg', array( 'tabs' => 'visual', 'toolbar' => 'basic', 'media_upload' => 0 ) ),
						webwise_acf_field( 'about_service_cta_label', 'Button label', 'cta_label' ),
						webwise_acf_field( 'about_service_link', 'Button link', 'link', 'link', array( 'return_format' => 'array' ) ),
					),
				) ),
				webwise_acf_field( 'tab_about_campaign', 'Campaign banner', 'about_campaign_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'about_campaign_image', 'Image', 'about_campaign_image', 'image', array( 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all' ) ),
				webwise_acf_field( 'about_campaign_heading', 'Heading', 'about_campaign_heading', 'textarea', array( 'rows' => 2 ) ),
				webwise_acf_field( 'tab_about_press', 'Discovered For You', 'about_press_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'about_press_intro', 'Introductory text', 'about_press_intro' ),
				webwise_acf_field( 'about_press_heading', 'Heading', 'about_press_heading' ),
				webwise_acf_field( 'about_press_logos', 'Brand logos', 'about_press_logos', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add brand logo',
					'sub_fields'   => array(
						webwise_acf_field( 'about_press_logo_image', 'Logo image', 'image', 'image', array( 'return_format' => 'id', 'preview_size' => 'thumbnail', 'library' => 'all' ) ),
						webwise_acf_field( 'about_press_logo_link', 'Link', 'link', 'link', array( 'return_format' => 'array' ) ),
					),
				) ),
				webwise_acf_field( 'tab_about_social', 'Social gallery', 'about_social_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'about_social_heading', 'Heading', 'about_social_heading' ),
				webwise_acf_field( 'about_social_images', 'Social images', 'about_social_images', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add social image',
					'sub_fields'   => array(
						webwise_acf_field( 'about_social_image', 'Image', 'image', 'image', array( 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all' ) ),
						webwise_acf_field( 'about_social_link', 'Link', 'link', 'link', array( 'return_format' => 'array' ) ),
					),
				) ),
			),
			'location'              => $about_location,
			'menu_order'            => 1,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
		)
	);

	$register_group(
		array(
			'key'                   => 'group_webwise_customer_service',
			'title'                 => 'WebWise Customer Service Page',
			'fields'                => array(
				webwise_acf_field( 'customer_service_tab_hero', 'Hero', 'customer_service_hero_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'customer_service_hero_image', 'Hero image', 'customer_service_hero_image', 'image', array( 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all' ) ),
				webwise_acf_field( 'customer_service_hero_heading', 'Hero heading', 'customer_service_hero_heading' ),
				webwise_acf_field( 'customer_service_tab_content', 'Customer service content', 'customer_service_content_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'customer_service_heading', 'Page heading', 'customer_service_heading' ),
				webwise_acf_field( 'customer_service_categories', 'Support categories', 'customer_service_categories', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add support category',
					'sub_fields'   => array(
						webwise_acf_field( 'customer_service_category_key', 'Category key', 'key', 'select', array(
							'choices'       => array(
								'my-orders'     => 'My Orders',
								'payment'       => 'Payment',
								'shipping'      => 'Shipping',
								'return-refund' => 'Return & Refund',
							),
							'return_format' => 'value',
							'ui'            => 1,
						) ),
						webwise_acf_field( 'customer_service_category_label', 'Category label', 'label' ),
						webwise_acf_field( 'customer_service_category_icon', 'Category icon', 'icon', 'image', array( 'return_format' => 'id', 'preview_size' => 'thumbnail', 'library' => 'all' ) ),
					),
				) ),
				webwise_acf_field( 'customer_service_faqs', 'Frequently asked questions', 'customer_service_faqs', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add question',
					'collapsed'    => 'field_webwise_customer_service_faq_question',
					'sub_fields'   => array(
						webwise_acf_field( 'customer_service_faq_category', 'Category', 'category', 'select', array(
							'choices'       => array(
								'my-orders'    => 'My Orders',
								'payment'      => 'Payment',
								'shipping'     => 'Shipping',
								'return-refund' => 'Return & Refund',
							),
							'return_format' => 'value',
							'ui'            => 1,
						) ),
						webwise_acf_field( 'customer_service_faq_question', 'Question', 'question' ),
						webwise_acf_field( 'customer_service_faq_answer', 'Answer', 'answer', 'textarea', array( 'rows' => 3 ) ),
					),
				) ),
			),
			'location'              => $customer_service_location,
			'menu_order'            => 1,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
		)
	);

	$register_group(
		array(
			'key'                   => 'group_webwise_privacy_policy',
			'title'                 => 'WebWise Privacy Policy Page',
			'fields'                => array(
				webwise_acf_field( 'privacy_policy_tab_hero', 'Hero', 'privacy_policy_hero_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'privacy_policy_hero_image', 'Hero image', 'privacy_policy_hero_image', 'image', array( 'return_format' => 'id', 'preview_size' => 'large', 'library' => 'all' ) ),
				webwise_acf_field( 'privacy_policy_hero_heading', 'Hero heading', 'privacy_policy_hero_heading' ),
				webwise_acf_field( 'privacy_policy_tab_content', 'Policy content', 'privacy_policy_content_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'privacy_policy_heading', 'Policy heading', 'privacy_policy_heading' ),
				webwise_acf_field( 'privacy_policy_updated', 'Last updated', 'privacy_policy_updated' ),
				webwise_acf_field( 'privacy_policy_sidebar_links', 'Policy navigation links', 'privacy_policy_sidebar_links', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add navigation link',
					'sub_fields'   => array(
						webwise_acf_field( 'privacy_policy_sidebar_label', 'Label', 'label' ),
						webwise_acf_field( 'privacy_policy_sidebar_link', 'Link', 'link', 'link', array( 'return_format' => 'array' ) ),
					),
				) ),
				webwise_acf_field( 'privacy_policy_sections', 'Policy sections', 'privacy_policy_sections', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add policy section',
					'collapsed'    => 'field_webwise_privacy_policy_section_heading',
					'sub_fields'   => array(
						webwise_acf_field( 'privacy_policy_section_heading', 'Section heading', 'heading' ),
						webwise_acf_field( 'privacy_policy_section_content', 'Section content', 'content', 'wysiwyg', array(
							'tabs'         => 'visual',
							'toolbar'      => 'basic',
							'media_upload' => 0,
						) ),
					),
				) ),
			),
			'location'              => $privacy_policy_location,
			'menu_order'            => 1,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
		)
	);

	$register_group(
		array(
			'key'                   => 'group_webwise_footer',
			'title'                 => 'WebWise Footer',
			'fields'                => array(
				webwise_acf_field( 'tab_footer_brand', 'Brand', 'footer_brand_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'footer_logo', 'Footer logo', 'footer_logo', 'image', array( 'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all' ) ),
				webwise_acf_field( 'footer_description', 'Footer description', 'footer_description', 'textarea', array( 'rows' => 3 ) ),
				webwise_acf_field( 'tab_footer_services', 'Service panels', 'footer_services_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'footer_services', 'Service and contact panels', 'footer_services', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add footer panel',
					'sub_fields'   => array(
						webwise_acf_field( 'footer_service_icon', 'Icon image', 'icon', 'image', array( 'return_format' => 'id', 'preview_size' => 'thumbnail', 'library' => 'all' ) ),
						webwise_acf_field( 'footer_service_title', 'Title', 'title' ),
						webwise_acf_field( 'footer_service_copy', 'Description', 'copy', 'textarea', array( 'rows' => 2 ) ),
						webwise_acf_field( 'footer_service_button_label', 'Button label', 'button_label' ),
						webwise_acf_field( 'footer_service_input_placeholder', 'Email placeholder', 'input_placeholder' ),
						webwise_acf_field( 'footer_service_link', 'Link', 'link', 'link', array( 'return_format' => 'array' ) ),
					),
				) ),
				webwise_acf_field( 'tab_footer_quick_links', 'Quick links', 'footer_quick_links_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'footer_quick_links_heading', 'Quick links heading', 'footer_quick_links_heading' ),
				webwise_acf_field( 'footer_quick_links', 'Quick links', 'footer_quick_links', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add quick link',
					'sub_fields'   => array(
						webwise_acf_field( 'footer_quick_link_label', 'Label', 'label' ),
						webwise_acf_field( 'footer_quick_link', 'Link', 'link', 'link', array( 'return_format' => 'array' ) ),
					),
				) ),
				webwise_acf_field( 'tab_footer_legal_links', 'Legal links', 'footer_legal_links_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'footer_legal_links_heading', 'Legal links heading', 'footer_legal_links_heading' ),
				webwise_acf_field( 'footer_legal_links', 'Legal links', 'footer_legal_links', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add legal link',
					'sub_fields'   => array(
						webwise_acf_field( 'footer_legal_link_label', 'Label', 'label' ),
						webwise_acf_field( 'footer_legal_link', 'Link', 'link', 'link', array( 'return_format' => 'array' ) ),
					),
				) ),
				webwise_acf_field( 'tab_footer_account_links', 'Account links', 'footer_account_links_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'footer_account_links_heading', 'Account links heading', 'footer_account_links_heading' ),
				webwise_acf_field( 'footer_account_links', 'Account links', 'footer_account_links', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add account link',
					'sub_fields'   => array(
						webwise_acf_field( 'footer_account_link_label', 'Label', 'label' ),
						webwise_acf_field( 'footer_account_link', 'Link', 'link', 'link', array( 'return_format' => 'array' ) ),
					),
				) ),
				webwise_acf_field( 'tab_footer_social', 'Social links', 'footer_social_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'footer_social_heading', 'Social links heading', 'footer_social_heading' ),
				webwise_acf_field( 'footer_social_links', 'Social media links', 'footer_social_links', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add social link',
					'sub_fields'   => array(
						webwise_acf_field( 'footer_social_name', 'Platform name', 'name' ),
						webwise_acf_field( 'footer_social_link', 'Profile link', 'link', 'link', array( 'return_format' => 'array' ) ),
					),
				) ),
				webwise_acf_field( 'tab_footer_payment', 'Payment and copyright', 'footer_payment_tab', 'tab', array( 'placement' => 'top' ) ),
				webwise_acf_field( 'footer_payment_heading', 'Payment methods heading', 'footer_payment_heading' ),
				webwise_acf_field( 'footer_payment_logos', 'Payment logos', 'footer_payment_logos', 'repeater', array(
					'layout'       => 'row',
					'button_label' => 'Add payment logo',
					'sub_fields'   => array(
						webwise_acf_field( 'footer_payment_logo', 'Logo image', 'image', 'image', array( 'return_format' => 'id', 'preview_size' => 'thumbnail', 'library' => 'all' ) ),
					),
				) ),
				webwise_acf_field( 'footer_copyright', 'Copyright text', 'footer_copyright' ),
				webwise_acf_field( 'footer_support_link', 'Floating support link', 'footer_support_link', 'link', array( 'return_format' => 'array' ) ),
			),
			'location'              => $options_location,
			'menu_order'            => 2,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
		)
	);
}
add_action( 'acf/init', 'webwise_register_homepage_acf_fields' );

function webwise_acf_dependency_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		$message = __( 'The WebWise homepage fields require Advanced Custom Fields to be active.', 'twentytwentyfour-child' );
	} elseif (
		! function_exists( 'acf_add_options_page' )
		|| ! function_exists( 'acf_get_field_type' )
		|| ! acf_get_field_type( 'repeater' )
	) {
		$message = __( 'The WebWise homepage uses Repeater fields; activate Advanced Custom Fields PRO to edit all homepage sections.', 'twentytwentyfour-child' );
	} else {
		return;
	}

	printf(
		'<div class="notice notice-error"><p>%s</p></div>',
		esc_html( $message )
	);
}
add_action( 'admin_notices', 'webwise_acf_dependency_notice' );
