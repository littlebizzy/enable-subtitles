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

// Display the subtitle for a post or page
function display_subtitle() {
    global $post;

    if ( isset( $post->subtitle ) ) {
        echo '<h2 class="subtitle">' . esc_html( $post->subtitle ) . '</h2>';
    }
}
add_shortcode( 'subtitle', 'display_subtitle' );

// Add subtitle support to posts and pages
function enable_subtitle_support() {
    add_post_type_support( 'post', 'subtitle' );
    add_post_type_support( 'page', 'subtitle' );
}
add_action( 'init', 'enable_subtitle_support' );

// Admin functions
if ( is_admin() ) {
    // Add a meta box for subtitles
    function add_subtitle_meta_box() {
        add_meta_box( 'subtitle_meta_box', 'Subtitle', 'render_subtitle_meta_box', 'post', 'normal', 'high' );
        add_meta_box( 'subtitle_meta_box', 'Subtitle', 'render_subtitle_meta_box', 'page', 'normal', 'high' );
    }

    // Render the subtitle meta box
    function render_subtitle_meta_box( $post ) {
        wp_nonce_field( 'subtitle_nonce_action', 'subtitle_nonce' );
        $subtitle = get_post_meta( $post->ID, 'subtitle', true );
        echo '<label for="subtitle">Subtitle: </label>';
        echo '<input type="text" id="subtitle" name="subtitle" value="' . esc_attr( $subtitle ) . '" />';
    }

    // Save the subtitle meta
    function save_subtitle( $post_id ) {
        if ( ! isset( $_POST['subtitle_nonce'] ) || ! wp_verify_nonce( $_POST['subtitle_nonce'], 'subtitle_nonce_action' ) ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        if ( isset( $_POST['subtitle'] ) ) {
            update_post_meta( $post_id, 'subtitle', sanitize_text_field( $_POST['subtitle'] ) );
        }
    }

    add_action( 'add_meta_boxes', 'add_subtitle_meta_box' );
    add_action( 'save_post', 'save_subtitle' );
}

// Ref: ChatGPT
