<?php

namespace Honeycomb\Services;

use Nectary\Services\Feed_Service;
use Nectary\Models\Rss_Feed;

// TODO remove dependency on Nectary
class WordPress_Feed_Service extends Feed_Service {
	/**
	 * @param $url
	 * @return Rss_Feed
	 */
	public function get_feed( $url ) : Rss_Feed {
		return new Rss_Feed( $url, array( $this, 'fetch_feed' ) );
	}

	/**
	 * @param  $url
	 * @return mixed
	 * @throws \Exception
	 */
	public function fetch_feed( $url ) {
		if ( \function_exists( 'fetch_feed' ) ) {
			include_once ABSPATH . WPINC . '/feed.php';

			$feed = fetch_feed( $url );

			if ( ! is_wp_error( $feed ) ) {
				return $feed;
			}

			throw new \Exception( 'Could not load WordPress feed: ' . $feed->get_error_message() );
		}

		throw new \Exception( 'Required file missing to import feed' );
	}
}
