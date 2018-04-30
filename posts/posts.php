<?php

// Subpackage namespace
namespace LittleBizzy\EnableSubtitles\Posts;

/**
 * Posts class
 *
 * @package Enable Subtitles
 * @subpackage Posts
 */
class Posts {



	/**
	 * Constructor
	 */
	public function __construct() {

		// Hook the post data
		add_action('the_post', [&$this, 'the_post']);

		// Hook the query results
		add_filter('the_posts', [&$this, 'the_posts']);
	}



	/**
	 * WP_Query the_posts hook
	 */
	public function the_posts($posts) {

		// Check results
		if (empty($posts) || !is_array($posts))
			return $posts;

		// Enum results
		foreach ($posts as &$post) {

			// Check post
			if (!is_object($post) || !is_a($post, 'WP_Post') || empty($post->ID))
				continue;

			// Check post_subtitle property
			if (!isset($post->post_subtitle))
				$post->post_subtitle = get_the_subtitle($post);
		}

		// Done
		return $posts;
	}



	/**
	 * Hook from WP_Query setup_postdata method
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