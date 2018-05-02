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

		// Prepare prefix
		$prefix = '#'.esc_html($this->plugin->prefix).'-';

		// Styles ?>
<style type="text/css">

	<?php echo $prefix; ?>subtitlediv {
		position: relative;
		margin-bottom: 10px;
		margin-top: -5px;
	}
	.version-4-1 <?php echo $prefix; ?>subtitlediv { margin-bottom: 0; margin-top: 0; }

		#poststuff <?php echo $prefix; ?>subtitlewrap {
			border: 0;
			padding: 0;
		}

			<?php echo $prefix; ?>subtitlediv <?php echo $prefix; ?>subtitle {
				clear: both;
				display: block;
				padding: 3px 8px;
				font-size: 18px;
				line-height: 100%;
				height: 38px;
				width: 100%;
				outline: none;
				margin: 10px 0 0;
				background-color: #fff;
			}

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

		// Nonce field
		wp_nonce_field(basename($this->plugin->root), $this->plugin->prefix.'_nonce', false);

		// HTML ?>
		<div id="<?php echo esc_attr($this->plugin->prefix.'-subtitlediv'); ?>">
			<div id="<?php echo esc_attr($this->plugin->prefix.'-subtitlewrap'); ?>">
				<input type="text" name="<?php echo esc_attr($this->plugin->prefix.'_subtitle'); ?>" size="30" value="<?php echo esc_attr(''.get_post_meta($post->ID, 'subtitle', true)); ?>" id="<?php echo esc_attr($this->plugin->prefix.'-subtitle'); ?>" autocomplete="off" />
			</div><!-- #subtitlewrap -->
		</div><!-- #subtitlediv --><?php
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
			!isset($_POST[$this->plugin->prefix.'_subtitle']) ||
			!$this->isPostTypeSupported($post->post_type) ||
			!$this->currentUserCanEditSubtitle($post_id, $post->post_type) ||
			empty($_POST[$this->plugin->prefix.'_nonce']) || !wp_verify_nonce($_POST[$this->plugin->prefix.'_nonce'], basename($this->plugin->root))) {

			// Out
			return;
		}

		// Just udate
		update_post_meta($post_id, 'subtitle', $_POST[$this->plugin->prefix.'_subtitle']);
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