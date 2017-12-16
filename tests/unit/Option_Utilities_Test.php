<?php

namespace Honeycomb\Tests\Unit;

use Honeycomb\Utilities\Option_Utilities;
use PHPUnit\Framework\TestCase;

/**
 * @group utility
 * @group option
 */
class Option_Utilities_Test extends TestCase {
	protected function setUp() {
		update_option(
				'namespace',
				array(
					'key' => 'value',
					'null_key' => null
				)
		);
	}

	public function test_exists() {
		$this->assertEquals( 'Honeycomb\Utilities\Option_Utilities', Option_Utilities::class );
		$this->assertTrue( class_exists( Option_Utilities::class ) );
	}

	public function test_can_get_option() {
		$value = Option_Utilities::get( 'namespace', 'key' );
		$this->assertEquals( 'value', $value );
	}

	public function test_will_get_null() {
		$value = Option_Utilities::get( 'namespace', 'wrong_key' );
		$this->assertEquals( null, $value );

		$value = Option_Utilities::get( 'wrong_namespace', 'key' );
		$this->assertEquals( null, $value );
	}

	public function test_will_provide_default() {
		$value = Option_Utilities::get_or_default( 'namespace', 'wrong_key', '???' );
		$this->assertEquals( '???', $value );

		$value = Option_Utilities::get_or_default( 'wrong_namespace', 'key', '???' );
		$this->assertEquals( '???', $value );
	}

	public function test_will_provide_real_value_even_when_given_default() {
		$value = Option_Utilities::get_or_default( 'namespace', 'key', '???' );
		$this->assertEquals( 'value', $value );
	}

	public function test_will_provide_null_if_null_is_real_value() {
		$value = Option_Utilities::get_or_default( 'namespace', 'null_key', '???' );
		$this->assertEquals( null, $value );
	}
}
