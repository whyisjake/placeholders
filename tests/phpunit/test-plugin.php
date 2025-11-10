<?php
/**
 * Plugin tests
 *
 * @package Placeholders
 */

/**
 * Test plugin constants and setup
 */
class Test_Plugin extends WP_UnitTestCase {

	/**
	 * Test that plugin constants are defined
	 */
	public function test_constants_defined() {
		$this->assertTrue( defined( 'PLACEHOLDERS_VERSION' ) );
		$this->assertTrue( defined( 'PLACEHOLDERS_PLUGIN_DIR' ) );
		$this->assertTrue( defined( 'PLACEHOLDERS_PLUGIN_URL' ) );
	}

	/**
	 * Test that version constant matches plugin header
	 */
	public function test_version_constant() {
		$this->assertEquals( '1.0.0', PLACEHOLDERS_VERSION );
	}

	/**
	 * Test that ad sizes function returns array
	 */
	public function test_ad_sizes_function_exists() {
		$this->assertTrue( function_exists( 'placeholders_get_ad_sizes' ) );
		$ad_sizes = placeholders_get_ad_sizes();
		$this->assertIsArray( $ad_sizes );
		$this->assertNotEmpty( $ad_sizes );
	}

	/**
	 * Test that all 14 ad sizes are defined
	 */
	public function test_all_ad_sizes_defined() {
		$ad_sizes = placeholders_get_ad_sizes();
		$this->assertCount( 14, $ad_sizes );

		$expected_sizes = array(
			'leaderboard',
			'medium-rectangle',
			'wide-skyscraper',
			'mobile-banner',
			'billboard',
			'large-rectangle',
			'half-page',
			'small-square',
			'square',
			'small-rectangle',
			'vertical-rectangle',
			'large-leaderboard',
			'portrait',
			'netboard',
		);

		foreach ( $expected_sizes as $size ) {
			$this->assertArrayHasKey( $size, $ad_sizes );
		}
	}

	/**
	 * Test that each ad size has required properties
	 */
	public function test_ad_size_properties() {
		$ad_sizes = placeholders_get_ad_sizes();

		foreach ( $ad_sizes as $slug => $size ) {
			$this->assertArrayHasKey( 'width', $size, "Size $slug is missing width" );
			$this->assertArrayHasKey( 'height', $size, "Size $slug is missing height" );
			$this->assertArrayHasKey( 'label', $size, "Size $slug is missing label" );

			$this->assertIsInt( $size['width'], "Width for $slug is not an integer" );
			$this->assertIsInt( $size['height'], "Height for $slug is not an integer" );
			$this->assertIsString( $size['label'], "Label for $slug is not a string" );
		}
	}
}
