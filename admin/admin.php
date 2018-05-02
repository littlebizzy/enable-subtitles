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



	// Properties and constants
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Object plugin
	 */
	private $plugin;



	/**
	 * Unsuportted post types
	 */
	const POST_TYPE_EXCEPTIONS = 'attachment,revision';



	// Initialization
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Constructor
	 */
	public function __construct($plugin) {

		// Set properties
		$this->plugin = $plugin;

		// Admin head
		add_action('admin_head', [&$this, 'addSubtitleStyles']);

		// After title hook
		add_action('edit_form_before_permalink', [&$this, 'addSubtitleField']);

		// Save post hook
		add_action('save_post', [&$this, 'updateSubtitleValue'], 10, 2);
	}



	/**
	 * Add in-page styles just for the edit post screen
	 */
	public function addSubtitleStyles() {

		// Check screen
		$screen = get_current_screen();
		if (empty($screen) ||
			empty($screen->base) ||
			!in_array($screen->base, ['post']) ||
			empty($screen->post_type) ||
			!$this->isPostTypeSupported($screen->post_type)) {

			// Out
			return;
		}

		// Styles ?>
<style type="text/css">
/* hi there */
</style><?php
	}



	/**
	 * Shows the proper HTML corresponding with the new subtitle field
	 */
	public function addSubtitleField($post) {

		// Check post
		if (empty($post->ID) ||
			empty($post->post_type) ||
			!$this->isPostTypeSupported($post->post_type) ||
			!$this->currentUserCanEditSubtitle($post->ID, $post->post_type)) {

			// Out
			return;
		}

		echo '<div>test</div>';
	}



	/**
	 * Update subtitle data
	 */
	public function updateSubtitleValue($post_id, $post) {

		// Check post current status
		if (empty($post_id) ||
			empty($post->post_type) ||
			wp_is_post_autosave($post_id) ||
			wp_is_post_revision($post_id) ||
			!$this->isPostTypeSupported($post->post_type) ||
			!$this->currentUserCanEditSubtitle($post_id, $post->post_type)) {

			// Out
			return;
		}

		// Check nonce
		// ...
	}



	/**
	 * Determines if the post can be edited by the current user
	 */
	private function currentUserCanEditSubtitle($post_id, $post_type) {
		$post_type_object = (object) get_post_type_object($post_type);
		return !empty($post_type_object) && is_object($post_type_object) && current_user_can($post_type_object->cap->edit_post, $post_id);
	}



	/**
	 * Decides whether a post type is supported
	 */
	private function isPostTypeSupported($postType)  {
		$exceptions = array_map('trim', explode(',', self::POST_TYPE_EXCEPTIONS));
		return !in_array($postType, $exceptions);
	}



}