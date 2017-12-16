<?php

namespace Honeycomb\Utilities;

class Option_Utilities {
	/**
	 * @param  string $namespace
	 * @param  string $key
	 * @return mixed
	 */
	public static function get( string $namespace, string $key ) {
		$options = get_option( $namespace );

		if ( self::key_exists( $namespace, $key ) ) {
			return esc_attr( $options[ $key ] );
		}

		return null;
	}

	/**
	 * @param  string $namespace
	 * @param  string $key
	 * @param  mixed  $default
	 * @return mixed
	 */
	public static function get_or_default( string $namespace, string $key, $default ) {
		$value = self::get( $namespace, $key );

		if ( $value === null && ! self::key_exists( $namespace, $key ) ) {
			return $default;
		}

		return $value;
	}

	/**
	 * @param $namespace
	 * @param $key
	 * @return bool
	 */
	public static function key_exists( $namespace, $key ) : bool {
		$options = get_option( $namespace );
		return \is_array( $options ) && array_key_exists( $key, $options );
	}
}
