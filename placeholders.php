<?php
/**
 * Plugin Name: Placeholders
 * Plugin URI: https://github.com/whyisjake/placeholders
 * Description: Gutenberg blocks for common ad placeholder sizes
 * Version: 1.0.0
 * Author: Jake Spurlock
 * Author URI: https://whyisjake.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: placeholders
 *
 * @package Placeholders
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'PLACEHOLDERS_VERSION', '1.0.0' );
define( 'PLACEHOLDERS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PLACEHOLDERS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Common ad sizes with their dimensions.
 */
function placeholders_get_ad_sizes() {
	return array(
		'leaderboard'        => array(
			'width'  => 728,
			'height' => 90,
			'label'  => 'Leaderboard',
		),
		'medium-rectangle'   => array(
			'width'  => 300,
			'height' => 250,
			'label'  => 'Medium Rectangle',
		),
		'wide-skyscraper'    => array(
			'width'  => 160,
			'height' => 600,
			'label'  => 'Wide Skyscraper',
		),
		'mobile-banner'      => array(
			'width'  => 320,
			'height' => 50,
			'label'  => 'Mobile Banner',
		),
		'billboard'          => array(
			'width'  => 970,
			'height' => 250,
			'label'  => 'Billboard',
		),
		'large-rectangle'    => array(
			'width'  => 336,
			'height' => 280,
			'label'  => 'Large Rectangle',
		),
		'half-page'          => array(
			'width'  => 300,
			'height' => 600,
			'label'  => 'Half Page',
		),
		'small-square'       => array(
			'width'  => 200,
			'height' => 200,
			'label'  => 'Small Square',
		),
		'square'             => array(
			'width'  => 250,
			'height' => 250,
			'label'  => 'Square',
		),
		'small-rectangle'    => array(
			'width'  => 180,
			'height' => 150,
			'label'  => 'Small Rectangle',
		),
		'vertical-rectangle' => array(
			'width'  => 240,
			'height' => 400,
			'label'  => 'Vertical Rectangle',
		),
		'large-leaderboard'  => array(
			'width'  => 970,
			'height' => 90,
			'label'  => 'Large Leaderboard',
		),
		'portrait'           => array(
			'width'  => 300,
			'height' => 1050,
			'label'  => 'Portrait',
		),
		'netboard'           => array(
			'width'  => 580,
			'height' => 400,
			'label'  => 'Netboard',
		),
	);
}

/**
 * Register custom block category.
 *
 * @param array $categories Array of block categories.
 * @return array Modified array of block categories.
 */
function placeholders_register_category( $categories ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'placeholders',
				'title' => 'Placeholders',
				'icon'  => 'format-image',
			),
		)
	);
}
add_filter( 'block_categories_all', 'placeholders_register_category' );

/**
 * Register all placeholder blocks.
 */
function placeholders_register_blocks() {
	$ad_sizes = placeholders_get_ad_sizes();

	foreach ( $ad_sizes as $slug => $size ) {
		register_block_type(
			PLACEHOLDERS_PLUGIN_DIR . 'blocks/' . $slug,
			array(
				'render_callback' => 'placeholders_render_block',
			)
		);
	}
}
add_action( 'init', 'placeholders_register_blocks' );

/**
 * Render a single ad placeholder.
 *
 * @param array  $size         Ad size data.
 * @param string $size_slug    Ad size slug.
 * @param string $bg_color     Background color.
 * @param string $text_color   Text color.
 * @param string $breakpoint   Breakpoint class (desktop, tablet, mobile).
 * @return string Rendered ad placeholder HTML.
 */
function placeholders_render_single_ad( $size, $size_slug, $bg_color, $text_color, $breakpoint = 'desktop' ) {
	$classes = sprintf(
		'placeholder-ad placeholder-ad-%s placeholder-ad-size-%s',
		esc_attr( $size_slug ),
		esc_attr( $breakpoint )
	);

	return sprintf(
		'<div class="%s" style="width: %dpx; height: %dpx; background-color: %s; color: %s;"><div class="placeholder-ad-content"><span class="placeholder-ad-label">%s</span><span class="placeholder-ad-dimensions">%d × %d</span></div></div>',
		$classes,
		$size['width'],
		$size['height'],
		$bg_color,
		$text_color,
		esc_html( $size['label'] ),
		$size['width'],
		$size['height']
	);
}

/**
 * Render callback for placeholder blocks.
 *
 * @param array  $attributes Block attributes.
 * @param string $content    Block content.
 * @param object $block      Block object.
 * @return string Rendered block HTML.
 */
function placeholders_render_block( $attributes, $content, $block ) {
	$block_name = str_replace( 'placeholders/', '', $block->name );
	$ad_sizes   = placeholders_get_ad_sizes();

	if ( ! isset( $ad_sizes[ $block_name ] ) ) {
		return '';
	}

	$size       = $ad_sizes[ $block_name ];
	$bg_color   = isset( $attributes['backgroundColor'] ) ? esc_attr( $attributes['backgroundColor'] ) : '#f0f0f0';
	$text_color = isset( $attributes['textColor'] ) ? esc_attr( $attributes['textColor'] ) : '#666666';
	$responsive = isset( $attributes['responsive'] ) ? $attributes['responsive'] : false;

	// Non-responsive mode: render single ad
	if ( ! $responsive ) {
		$wrapper_attributes = get_block_wrapper_attributes(
			array(
				'class' => 'placeholder-ad placeholder-ad-' . esc_attr( $block_name ),
				'style' => sprintf(
					'width: %dpx; height: %dpx; background-color: %s; color: %s;',
					$size['width'],
					$size['height'],
					$bg_color,
					$text_color
				),
			)
		);

		return sprintf(
			'<div %s><div class="placeholder-ad-content"><span class="placeholder-ad-label">%s</span><span class="placeholder-ad-dimensions">%d × %d</span></div></div>',
			$wrapper_attributes,
			esc_html( $size['label'] ),
			$size['width'],
			$size['height']
		);
	}

	// Responsive mode: render multiple ad sizes for different breakpoints
	// Render in order: mobile, tablet, desktop (for CSS sibling selectors)
	$mobile_size = isset( $attributes['mobileSize'] ) ? $attributes['mobileSize'] : '';
	$tablet_size = isset( $attributes['tabletSize'] ) ? $attributes['tabletSize'] : '';

	$output = '';

	// Mobile size (optional - rendered first)
	if ( ! empty( $mobile_size ) && isset( $ad_sizes[ $mobile_size ] ) ) {
		$output .= placeholders_render_single_ad( $ad_sizes[ $mobile_size ], $mobile_size, $bg_color, $text_color, 'mobile' );
	}

	// Tablet size (optional - rendered second)
	if ( ! empty( $tablet_size ) && isset( $ad_sizes[ $tablet_size ] ) ) {
		$output .= placeholders_render_single_ad( $ad_sizes[ $tablet_size ], $tablet_size, $bg_color, $text_color, 'tablet' );
	}

	// Desktop size (always rendered last as fallback)
	$output .= placeholders_render_single_ad( $size, $block_name, $bg_color, $text_color, 'desktop' );

	// Get wrapper attributes but remove color classes (colors are applied inline to individual ads)
	$wrapper_attributes = get_block_wrapper_attributes(
		array(
			'class' => 'placeholder-ad-responsive-container',
		)
	);

	// Strip color-related classes from the container wrapper
	$wrapper_attributes = preg_replace( '/\s*has-[a-z-]*-color/', '', $wrapper_attributes );
	$wrapper_attributes = preg_replace( '/\s*has-background/', '', $wrapper_attributes );
	$wrapper_attributes = preg_replace( '/\s*has-text-color/', '', $wrapper_attributes );

	return sprintf( '<div %s>%s</div>', $wrapper_attributes, $output );
}

/**
 * Enqueue block editor assets.
 */
function placeholders_enqueue_block_editor_assets() {
	wp_enqueue_script(
		'placeholders-blocks',
		PLACEHOLDERS_PLUGIN_URL . 'blocks.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor' ),
		PLACEHOLDERS_VERSION,
		false
	);

	wp_enqueue_style(
		'placeholders-editor',
		PLACEHOLDERS_PLUGIN_URL . 'editor.css',
		array(),
		PLACEHOLDERS_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'placeholders_enqueue_block_editor_assets' );

/**
 * Enqueue frontend and editor styles.
 */
function placeholders_enqueue_block_assets() {
	wp_enqueue_style(
		'placeholders-style',
		PLACEHOLDERS_PLUGIN_URL . 'style.css',
		array(),
		PLACEHOLDERS_VERSION
	);
}
add_action( 'enqueue_block_assets', 'placeholders_enqueue_block_assets' );
