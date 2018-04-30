<?php

// Subpackage namespace
namespace LittleBizzy\EnableSubtitles\Core;

// Aliased namespaces
use \LittleBizzy\EnableSubtitles\Admin;

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



}