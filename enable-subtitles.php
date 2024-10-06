<?php
/*
Plugin Name: Enable Subtitles
Plugin URI: https://www.littlebizzy.com/plugins/enable-subtitles
Description: Creates new the_subtitle function
Version: 2.0.0
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Disable WordPress.org updates for this plugin
add_filter( 'gu_override_dot_org', function( $overrides ) {
    $overrides[] = 'enable-subtitles/enable-subtitles.php';
    return $overrides;
}, 999 );

// Retrieve the subtitle for a post or page
function get_the_subtitle( $post_id = null ) {
    $post_id = $post_id ? $post_id : get_the_ID();
    $subtitle = get_post_meta( $post_id, '_subtitle', true ); // Retrieve subtitle
    return apply_filters( 'enable_subtitles_output', esc_html( $subtitle ), $post_id ); // Sanitize during output
}

// Display the subtitle for a post or page
function the_subtitle() {
    global $post;

    if ( isset( $post->ID ) ) {
        $subtitle = get_the_subtitle( $post->ID );
        echo '<h2 class="subtitle">' . $subtitle . '</h2>';
    }
}
add_shortcode( 'subtitle', 'the_subtitle' );

// Enable subtitle support for all public post types
function enable_subtitle_support() {
    // Enable subtitle support for standard post types
    add_post_type_support( 'post', 'subtitle' );
    add_post_type_support( 'page', 'subtitle' );

    // Automatically add subtitle support for all custom post types
    $post_types = get_post_types( ['public' => true], 'names' );
    foreach ( $post_types as $post_type ) {
        add_post_type_support( $post_type, 'subtitle' ); // Add subtitle support to each post type
    }
}

// Hooking into WordPress
add_action( 'init', 'enable_subtitle_support' );

// Admin functions
if ( is_admin() ) {
    // Add the subtitle input field after the post title
    function add_subtitle_field() {
        add_action( 'edit_form_before_permalink', 'render_subtitle_field' );
    }

    // Render the subtitle field
    function render_subtitle_field( $post ) {
        // Security nonce field for form submission verification
        wp_nonce_field( 'subtitle_nonce_action', 'subtitle_nonce' );

        // Retrieve the existing subtitle and sanitize during output
        $subtitle = esc_html( get_post_meta( $post->ID, '_subtitle', true ) );

        // Output the HTML for the subtitle input field
        echo '
        <div id="subtitlewrap">
            <input type="text" id="subtitle" name="post_subtitle" value="' . esc_attr( $subtitle ) . '" class="widefat" placeholder="' . esc_attr__( 'Add subtitle', 'enable-subtitles' ) . '" aria-label="' . esc_attr__( 'Subtitle', 'enable-subtitles' ) . '" size="30" spellcheck="true" autocomplete="off" style="font-size: 18px;"/>
        </div>';
    }

    // Save the subtitle meta
    function save_subtitle( $post_id ) {
        // Check nonce for security
        if ( ! isset( $_POST['subtitle_nonce'] ) || ! wp_verify_nonce( $_POST['subtitle_nonce'], 'subtitle_nonce_action' ) ) {
            return;
        }

        // Check user permissions
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Save or update the subtitle
        if ( isset( $_POST['post_subtitle'] ) ) {
            update_post_meta( $post_id, '_subtitle', sanitize_text_field( $_POST['post_subtitle'] ) );
        }
    }

    add_action( 'add_meta_boxes', 'add_subtitle_field' );
    add_action( 'save_post', 'save_subtitle' );
}

// Ref: ChatGPT
