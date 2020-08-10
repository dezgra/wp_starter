<?php
/**
 * Theme functions and definitions
 *
 * @package Imtech
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'THEME_VERSION', '1.0.0' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'theme_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function theme_setup() {
		$hook_result = apply_filters_deprecated( 'elementor_theme_load_textdomain', [ true ], '2.0', 'theme_load_textdomain' );
		if ( apply_filters( 'theme_load_textdomain', $hook_result ) ) {
			load_theme_textdomain( 'imtech', get_template_directory() . '/languages' );
		}

		$hook_result = apply_filters_deprecated( 'elementor_theme_register_menus', [ true ], '2.0', 'theme_register_menus' );
		if ( apply_filters( 'theme_register_menus', $hook_result ) ) {
			register_nav_menus( array( 'primary' => __( 'Primary', 'imtech' ) ) );
		}

		$hook_result = apply_filters_deprecated( 'elementor_theme_add_theme_support', [ true ], '2.0', 'theme_add_theme_support' );
		if ( apply_filters( 'theme_add_theme_support', $hook_result ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				array(
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
				)
			);
			add_theme_support(
				'custom-logo',
				array(
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				)
			);

			/*
			 * Editor Style.
			 */
			add_editor_style( 'editor-style.css' );

			/*
			 * WooCommerce.
			 */
			$hook_result = apply_filters_deprecated( 'elementor_theme_add_woocommerce_support', [ true ], '2.0', 'theme_add_woocommerce_support' );
			if ( apply_filters( 'theme_add_woocommerce_support', $hook_result ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'theme_setup' );

if ( ! function_exists( 'theme_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function theme_scripts_styles() {
		$enqueue_basic_style = apply_filters_deprecated( 'elementor_theme_enqueue_style', [ true ], '2.0', 'theme_enqueue_style' );
		$min_suffix          = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( apply_filters( 'theme_enqueue_style', $enqueue_basic_style ) ) {
			wp_enqueue_style(
				'style',
				get_template_directory_uri() . '/style' . $min_suffix . '.css',
				[],
				THEME_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'theme_scripts_styles' );

if ( ! function_exists( 'theme_widgets_init' ) ) {
    /**
     * Theme Widgets.
     */
    function theme_widgets_init()
    {
        register_sidebar(array(
            'name' => esc_html__('Footer widget 1', 'imtech'),
            'id' => 'footer-widget-1',
            'description' => esc_html__('Add widgets here to appear in your Sidebar.', 'imtech'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h2>',
        ));
        register_sidebar(array(
            'name' => esc_html__('Footer widget 2', 'imtech'),
            'id' => 'footer-widget-2',
            'description' => esc_html__('Add widgets here to appear in your Sidebar.', 'imtech'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h2>',
        ));
    }
}
add_action( 'widgets_init', 'theme_widgets_init' );

if ( ! function_exists( 'theme_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function theme_register_elementor_locations( $elementor_theme_manager ) {
		$hook_result = apply_filters_deprecated( 'elementor_theme_register_elementor_locations', [ true ], '2.0', 'theme_register_elementor_locations' );
		if ( apply_filters( 'theme_register_elementor_locations', $hook_result ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'theme_register_elementor_locations' );

if ( ! function_exists( 'theme_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function theme_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'theme_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'theme_content_width', 0 );

if ( is_admin() ) {
	require get_template_directory() . '/includes/admin-functions.php';
}

if ( ! function_exists( 'theme_register_navwalker' ) ) {
    /**
     * Register Theme NavWalker.
     */
    function theme_register_navwalker()
    {
        require_once get_template_directory() . '/includes/class-wp-bootstrap-navwalker.php';
    }
}
add_action( 'after_setup_theme', 'theme_register_navwalker' );

if ( ! function_exists( 'theme_check_hide_title' ) ) {
	/**
	 * Check hide title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function theme_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = \Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'theme_page_title', 'theme_check_hide_title' );

/**
 * Wrapper function to deal with backwards compatibility.
 */
if ( ! function_exists( 'theme_body_open' ) ) {
	function theme_body_open() {
		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
		} else {
			do_action( 'wp_body_open' );
		}
	}
}

/**
 * Remove all classes and ID from Nav Menu
 */
function remove_css_id_filter($var) {
    return is_array($var) ? array_intersect($var, array('nav-item', 'dropdown', 'current-menu-item')) : '';
}
add_filter('page_css_class', 'remove_css_id_filter', 100, 1);
add_filter('nav_menu_item_id', 'remove_css_id_filter', 100, 1);
add_filter('nav_menu_css_class', 'remove_css_id_filter', 100, 1);
