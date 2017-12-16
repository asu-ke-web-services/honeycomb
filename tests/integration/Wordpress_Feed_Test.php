<?php

namespace Honeycomb\Tests\Integration;

use Honeycomb\Services\Wordpress_Feed_Service;
use Nectary\Models\Rss_Feed;
use PHPUnit\Framework\TestCase;

/**
 * Test the WordPress Feed Test
 */
class Wordpress_Feed_Test extends TestCase {
	private $url;
	private $bad_url;

	protected function setUp() {
		$this->url     = 'https://news.wp.prod.gios.asu.edu/tag/board-letter/feed/';
		$this->bad_url = 'invalid://news.wp.prod.gios.asu.nope/tag/board-letter/feed/';

		// Disable https checking for testing
		add_filter( 'https_ssl_verify', '__return_false' );
	}

	public function test_returns_rss_feed() {
		$service = new Wordpress_Feed_Service();
		$rss_feed = $service->get_feed( $this->url );

		$this->assertInstanceOf( Rss_Feed::class, $rss_feed );
	}

	public function test_returns_rss_feed_data() {
		$service = new Wordpress_Feed_Service();
		$rss_feed = $service->get_feed( $this->url );
		$rss_feed->retrieve_items();

		$items = $rss_feed->get_items();

		$this->assertTrue( 0 < \count( $items ) );
	}

	/**
	 * @expectedException \Exception
	 */
	public function test_throws_error_when_invalid() {
		$service = new Wordpress_Feed_Service();
		$rss_feed = $service->get_feed( $this->bad_url );

		$rss_feed->retrieve_items();
	}
}
