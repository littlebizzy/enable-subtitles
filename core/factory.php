<?php

// Subpackage namespace
namespace LittleBizzy\EnableSubtitles\Core;

// Aliased namespaces
use \LittleBizzy\EnableSubtitles\Admin;
use \LittleBizzy\EnableSubtitles\Helpers;
use \LittleBizzy\EnableSubtitles\Posts;

/**
 * Object Factory class
 *
 * @package Enable Subtitles
 * @subpackage Core
 */
class Factory extends Helpers\Factory {



	/**
	 * Admin object
	 */
	protected function createAdmin() {
		return new Admin\Admin($this->plugin);
	}



	/**
	 * Posts object
	 */
	protected function createPosts() {
		return new Posts\Posts;
	}



	/**
	 * Shortcode object
	 */
	protected function createShortcode() {
		return new Posts\Shortcode;
	}



}