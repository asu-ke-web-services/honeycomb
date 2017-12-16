<?php

namespace Honeycomb\Services;

/**
 * Register class names for Hooks that should be run
 */
class Register {
	/**
	 * @param array $classes
	 */
	public function register( array $classes ) {
		foreach( $classes as $class_name ) {
			( new $class_name )->run();
		}
	}
}
