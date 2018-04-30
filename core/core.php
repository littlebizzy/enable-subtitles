<?php

// Subpackage namespace
namespace LittleBizzy\EnableSubtitles\Core;

/**
 * Core class
 *
 * @package Enable Subtitles
 * @subpackage Core
 */
final class Core {



	// Properties
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Single class instance
	 */
	private static $instance;



	/**
	 * Plugin object
	 */
	private $plugin;



	// Initialization
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Create or retrieve instance
	 */
	public static function instance($plugin = null) {

		// Check instance
		if (!isset(self::$instance))
			self::$instance = new self($plugin);

		// Done
		return self::$instance;
	}



	/**
	 * Constructor
	 */
	private function __construct($plugin) {
		$this->initialize($plugin);
		$this->checkWPContext();
	}



	/**
	 * Init objects
	 */
	public function initialize($plugin) {

		// Copy plugin object
		$this->plugin = $plugin;

		// Init factory object
		$this->plugin->factory = new Factory($plugin);

		// Load the functions file
		include $this->plugin->root.'/core/functions.php';

		// Hook the post data
		add_action('the_post', [&$this, 'the_post']);

		// Add the shortcode
		// ...
	}



	/**
	 * Check the current WP context
	 */
	public function checkWPContext() {

		// Avoid some contexts
		if ((defined('DOING_CRON') && DOING_CRON) ||
			(defined('DOING_AJAX') && DOING_AJAX) ||
			(defined('XMLRPC_REQUEST') && XMLRPC_REQUEST)) {
			return;
		}

		// Admin area
		if (is_admin()) {
			$this->plugin->factory->admin();
		}
	}



	// WP Hooks
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Hook from setup postdata
	 */
	public function the_post(&$post) {

		// Check input value
		if (!is_object($post) || !is_a($post, 'WP_Post') || empty($post->ID))
			return;

		// Check post_subtitle property
		if (!isset($post->post_subtitle))
			$post->post_subtitle = get_the_subtitle($post);
	}



}