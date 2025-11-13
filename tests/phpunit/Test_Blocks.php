<?php
/**
 * Block registration and rendering tests
 *
 * @package Placeholders
 */

/**
 * Test block registration and rendering
 */
class Test_Blocks extends WP_UnitTestCase {

	/**
	 * Test that all blocks are registered
	 */
	public function test_blocks_registered() {
		$ad_sizes = placeholders_get_ad_sizes();

		foreach ( $ad_sizes as $slug => $size ) {
			$block_name = 'placeholders/' . $slug;
			$this->assertTrue(
				WP_Block_Type_Registry::get_instance()->is_registered( $block_name ),
				"Block $block_name is not registered"
			);
		}
	}

	/**
	 * Test that each block has correct properties
	 */
	public function test_block_properties() {
		$ad_sizes = placeholders_get_ad_sizes();

		foreach ( $ad_sizes as $slug => $size ) {
			$block_name = 'placeholders/' . $slug;
			$block = WP_Block_Type_Registry::get_instance()->get_registered( $block_name );

			$this->assertNotNull( $block, "Block $block_name not found in registry" );
			$this->assertEquals( $block_name, $block->name );
			$this->assertNotNull( $block->render_callback );
		}
	}

	/**
	 * Test block rendering with default attributes
	 */
	public function test_block_render_default() {
		$block_content = '<!-- wp:placeholders/leaderboard /-->';
		$parsed_blocks = parse_blocks( $block_content );
		$output = render_block( $parsed_blocks[0] );

		$this->assertNotEmpty( $output );
		$this->assertStringContainsString( 'placeholder-ad', $output );
		$this->assertStringContainsString( 'Leaderboard', $output );
		$this->assertStringContainsString( '728', $output );
		$this->assertStringContainsString( '90', $output );
	}

	/**
	 * Test block rendering with custom colors
	 */
	public function test_block_render_custom_colors() {
		$block_content = '<!-- wp:placeholders/medium-rectangle {"backgroundColor":"#ff0000","textColor":"#ffffff"} /-->';
		$parsed_blocks = parse_blocks( $block_content );
		$output = render_block( $parsed_blocks[0] );

		$this->assertStringContainsString( '#ff0000', $output );
		$this->assertStringContainsString( '#ffffff', $output );
	}

	/**
	 * Test render callback escapes output
	 */
	public function test_block_render_escapes_output() {
		$block_content = '<!-- wp:placeholders/leaderboard {"backgroundColor":"<script>alert(\\"xss\\")</script>","textColor":"\"><script>alert(\\"xss\\")</script>"} /-->';
		$parsed_blocks = parse_blocks( $block_content );
		$output = render_block( $parsed_blocks[0] );

		// Verify dangerous tags are escaped and not executable
		$this->assertStringNotContainsString( '<script>', $output );
		$this->assertStringContainsString( '&lt;script&gt;', $output );

		// Verify quotes are escaped in attributes
		$this->assertStringContainsString( '&quot;', $output );
	}

	/**
	 * Test that invalid block name returns empty string
	 */
	public function test_invalid_block_name() {
		$block_content = '<!-- wp:placeholders/invalid-size /-->';
		$parsed_blocks = parse_blocks( $block_content );
		$output = render_block( $parsed_blocks[0] );
		$this->assertEmpty( $output );
	}

	/**
	 * Test responsive mode renders multiple ad sizes
	 */
	public function test_responsive_mode_renders_multiple_sizes() {
		$block_content = '<!-- wp:placeholders/leaderboard {"responsive":true,"mobileSize":"mobile-banner","tabletSize":"medium-rectangle"} /-->';
		$parsed_blocks = parse_blocks( $block_content );
		$output = render_block( $parsed_blocks[0] );

		// Should contain responsive container
		$this->assertStringContainsString( 'placeholder-ad-responsive-container', $output );

		// Should contain all three sizes
		$this->assertStringContainsString( 'placeholder-ad-size-mobile', $output );
		$this->assertStringContainsString( 'placeholder-ad-size-tablet', $output );
		$this->assertStringContainsString( 'placeholder-ad-size-desktop', $output );

		// Should contain mobile dimensions (320x50)
		$this->assertStringContainsString( '320', $output );
		$this->assertStringContainsString( '50', $output );

		// Should contain tablet dimensions (300x250)
		$this->assertStringContainsString( '300', $output );
		$this->assertStringContainsString( '250', $output );

		// Should contain desktop dimensions (728x90)
		$this->assertStringContainsString( '728', $output );
		$this->assertStringContainsString( '90', $output );
	}

	/**
	 * Test responsive mode with only mobile size
	 */
	public function test_responsive_mode_mobile_only() {
		$block_content = '<!-- wp:placeholders/leaderboard {"responsive":true,"mobileSize":"mobile-banner"} /-->';
		$parsed_blocks = parse_blocks( $block_content );
		$output = render_block( $parsed_blocks[0] );

		// Should contain responsive container
		$this->assertStringContainsString( 'placeholder-ad-responsive-container', $output );

		// Should contain mobile and desktop, but not tablet
		$this->assertStringContainsString( 'placeholder-ad-size-mobile', $output );
		$this->assertStringContainsString( 'placeholder-ad-size-desktop', $output );
		$this->assertStringNotContainsString( 'placeholder-ad-size-tablet', $output );
	}

	/**
	 * Test responsive mode handles invalid size gracefully
	 */
	public function test_responsive_mode_invalid_size() {
		$block_content = '<!-- wp:placeholders/leaderboard {"responsive":true,"mobileSize":"invalid-size"} /-->';
		$parsed_blocks = parse_blocks( $block_content );
		$output = render_block( $parsed_blocks[0] );

		// Should still render desktop size
		$this->assertStringContainsString( 'placeholder-ad-size-desktop', $output );
		// Should not crash or error
		$this->assertNotEmpty( $output );
	}

	/**
	 * Test custom block category is registered
	 */
	public function test_custom_category_registered() {
		$categories = placeholders_register_category( array() );

		$this->assertIsArray( $categories );
		$this->assertNotEmpty( $categories );

		$found = false;
		foreach ( $categories as $category ) {
			if ( isset( $category['slug'] ) && 'placeholders' === $category['slug'] ) {
				$found = true;
				$this->assertEquals( 'Placeholders', $category['title'] );
				$this->assertEquals( 'format-image', $category['icon'] );
			}
		}

		$this->assertTrue( $found, 'Placeholders category not found' );
	}
}
