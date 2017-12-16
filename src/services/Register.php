<?php

namespace Honeycomb\Services;

/**
 * Register class names for Hooks that should be run
 */
class Register {
	public function register( $classes ) {
		foreach( $classes as $class_name ) {
			( new $class_name )->run();
		}
	}
}
