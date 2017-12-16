<?php

namespace Honeycomb\Utilities;

class Option_Utilities {
	public static function get( $namespace, $key ) {
		$options = get_option( $namespace );

		if ( self::key_exists( $namespace, $key ) ) {
			return esc_attr( $options[ $key ] );
		}

		return null;
	}

	public static function get_or_default( $namespace, $key, $default ) {
		$value = self::get( $namespace, $key );

		if ( $value === null && ! self::key_exists( $namespace, $key ) ) {
			return $default;
		}

		return $value;
	}

	public static function key_exists( $namespace, $key ) {
		$options = get_option( $namespace );
		return \is_array( $options ) && array_key_exists( $key, $options );
	}
}
