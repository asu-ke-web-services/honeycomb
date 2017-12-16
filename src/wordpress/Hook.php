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

	protected function __construct( $plugin_slug, $version = '0.1' ) {
		$this->plugin_slug = $plugin_slug;
		$this->version     = $version;
	}

	public function add_action( $hook, $component, $callback, $priority = 10 ) {
		$this->actions = $this->add(
				$this->actions,
				$hook,
				$component,
				$callback,
				$priority
		);
	}

	public function add_filter( $hook, $component, $callback, $priority = 10 ) {
		$this->filters = $this->add(
				$this->filters,
				$hook,
				$component,
				$callback,
				$priority
		);
	}

	public function add_shortcode( $shortCodeName, $component, $callback ) {
		$this->shortcodes = $this->add(
				$this->shortcodes,
				$shortCodeName,
				$component,
				$callback
		);
	}

	private function add( $hooks, $hook, $component, $callback, $priority = 10 ) {
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
