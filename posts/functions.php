<?php

// Block direct calls
if (!function_exists('add_action'))
	die;



// Function check
if (!function_exists('the_subtitle')) :

	/**
	 * Shows or retrieve the current post subtitle
	 */
	function the_subtitle($before = '', $after = '', $echo = true) {

		// Retrieve data
		$subtitle = get_the_subtitle();

		// Check escaping
		$escapeHTML = apply_filters('post_subtitle_esc_html', true);

		// Prepare output
		$subtitle = $escapeHTML? esc_html($subtitle) : $subtitle;

		// Compose
		$subtitle = $before.$subtitle.$after;

		// Show
		if ($echo)
			echo $subtitle;

		// Done
		return $subtitle;
	}

endif;

// Subtitle action
add_action('the_subtitle', 'the_subtitle');



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
		$subtitle = empty($post_id)? '' : ''.get_post_meta($post_id, '_subtitle', true);

		// Execute filter
		return apply_filters('post_subtitle', $subtitle, $post_id);
	}

endif;

// Subtitle filter
add_filter('get_the_subtitle', 'get_the_subtitle');