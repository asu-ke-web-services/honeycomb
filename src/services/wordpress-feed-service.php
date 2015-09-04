<?php

namespace Honeycomb\Services;

use Nectary\Services\Feed_Service;
use Nectary\Models\Rss_Feed;

// TODO remove dependency on Nectary
class Wordpress_Feed_Service extends Feed_Service {
  public function get_feed( $url ) {
    return new Rss_Feed( $url, array( $this, 'fetch_feed' ) );
  }

  function fetch_feed( $url ) {
    if ( function_exists( 'fetch_feed' ) ) {
      include_once( ABSPATH . WPINC . '/feed.php' );

      $feed = fetch_feed( $url );

      if ( ! is_wp_error( $feed ) ) {
        return $feed;
      } else {
        throw new \Exception( 'Could not load WordPress feed' );
      }
    } else {
      throw new \Exception( 'Required file missing to import feed' );
    }
  }
}
