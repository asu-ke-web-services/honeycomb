<?php

namespace Honeycomb\Wordpress;

/**
 * Hook class
 *
 * Abstract class that all functionality should extend so that all functionality
 * has a common entry point
 */
abstract class Hook {
	protected $actions    = array();
	protected $filters    = array();
	protected $shortcodes = array();
	/** @type String */
	protected $plugin_slug;
	/** @type String */
	protected $version;

	public function load_dependencies() {
		// Do nothing by default.
	}

	/**
	 * Hook constructor.
	 *
	 * @param string $plugin_slug
	 * @param string $version
	 */
	protected function __construct( string $plugin_slug, string $version = '0.1' ) {
		$this->plugin_slug = $plugin_slug;
		$this->version     = $version;
	}

	/**
	 * @param string $hook
	 * @param Object $component
	 * @param string $callback
	 * @param int    $priority
	 */
	public function add_action( string $hook, Object $component, string $callback, int $priority = 10 ) {
		$this->actions = $this->add(
			$this->actions,
			$hook,
			$component,
			$callback,
			$priority
		);
	}

	/**
	 * @param string $hook
	 * @param Object $component
	 * @param string $callback
	 * @param int    $priority
	 */
	public function add_filter( string $hook, Object $component, string $callback, int $priority = 10 ) {
		$this->filters = $this->add(
			$this->filters,
			$hook,
			$component,
			$callback,
			$priority
		);
	}

	/**
	 * @param string $shortCodeName
	 * @param Object $component
	 * @param string $callback
	 */
	public function add_shortcode( string $shortCodeName, Object $component, string $callback ) {
		$this->shortcodes = $this->add(
			$this->shortcodes,
			$shortCodeName,
			$component,
			$callback
		);
	}

	/**
	 * @param array  $hooks
	 * @param string $hook
	 * @param Object $component
	 * @param string $callback
	 * @param int    $priority
	 * @return array
	 */
	private function add( array $hooks, string $hook, Object $component, string $callback, int $priority = 10 ) {
		$hooks[] = array(
			'hook'      => $hook,
			'component' => $component,
			'callback'  => $callback,
			'priority'  => $priority,
		);

		return $hooks;
	}

	public function run() {
		foreach ( $this->filters as $hook ) {
			add_filter(
				$hook['hook'],
				array( $hook['component'], $hook['callback'] )
			);
		}

		foreach ( $this->actions as $hook ) {
			add_action(
				$hook['hook'],
				array( $hook['component'], $hook['callback'] ), $hook['priority']
			);
		}

		foreach ( $this->shortcodes as $hook ) {
			add_shortcode(
				$hook['hook'],
				array( $hook['component'], $hook['callback'] )
			);
		}
	}
}
