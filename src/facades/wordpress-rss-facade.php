<?php

namespace Honeycomb\Facades;

use Nectary\Facades\Rss_Facade;

class Wordpress_Rss_Facade extends Rss_Facade {
  function load_dependencies() {
    if ( function_exists( 'fetch_feed' ) ) {
      include_once( ABSPATH . WPINC . '/feed.php' );

      return 'fetch_feed';
    } else {
      error_log( 'Required file missing to import feed' );
    }
  }
}
