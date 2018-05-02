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
		$this->factory = new Factory($plugin);
	}



	/**
	 * Check the current WP context
	 */
	public function checkWPContext() {

		// Load the functions file
		include $this->plugin->root.'/posts/functions.php';

		// Posts hooks
		$this->factory->posts();

		// Add the shortcode
		$this->factory->shortcode();

		// Admin area
		if (is_admin())
			$this->factory->admin();
	}



}