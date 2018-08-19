<?php

// Subpackage namespace
namespace LittleBizzy\EnableSubtitles\Posts;

/**
 * Shortcode class
 *
 * @package Enable Subtitles
 * @subpackage Posts
 */
class Shortcode {



	/**
	 * Constructor
	 */
	public function __construct() {
		add_shortcode('the_subtitle', [&$this, 'handler']);
	}



	/**
	 * Handle the shortcode output
	 */
	public function handler($atts) {

		// Merge default attributes
		$atts = shortcode_atts(['post' => 0], $atts);

		// Prepare output
		$escapeHTML = apply_filters('post_subtitle_esc_html', true);

		// Check post by attributes and retrieve data
		$subtitle = get_the_subtitle($atts['post']);

		// Done
		echo $escapeHTML? esc_html($subtitle) : $subtitle;
	}



}