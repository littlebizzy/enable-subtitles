<?php

// Function check
if (!function_exists('the_subtitle')) :

	/**
	 * Shows or retrieve the current post subtitle
	 */
	function the_subtitle($before = '', $after = '', $echo = true) {

		// Retrieve data
		$subtitle = get_the_subtitle();

		// Check value
		if (0 == strlen($subtitle))
			return '';

		// Compose
		$subtitle = $before.$title.$after;

		// Show
		if ($echo)
			echo $subtitle;

		// Done
		return $subtitle;
	}

endif;

// Function check
if (!function_exists('get_the_subtitle')) :

	/**
	 * Retrieve the subtitle value for a given post
	 */
	function get_the_subtitle($post = 0) {

		// Retrieve post
		$post = get_post($post);

		// Check Id
		$post_id = empty($post->ID)? 0 : $post->ID;

		// Retrieve data
		$subtitle = empty($post_id)? '' : ''.get_post_meta($post_id, 'subtitle', true);

		// Execute filter
		return apply_filters('the_subtitle', $subtitle, $post_id);
	}

endif;