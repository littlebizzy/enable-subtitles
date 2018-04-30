<?php

// Subpackage namespace
namespace LittleBizzy\EnableSubtitles\Admin;

/**
 * Admin class
 *
 * @package Enable Subtitles
 * @subpackage Admin
 */
class Admin {



	// Properties
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Object plugin
	 */
	private $plugin;



	// Initialization
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Constructor
	 */
	public function __construct($plugin) {

		// Set properties
		$this->plugin = $plugin;
	}



}