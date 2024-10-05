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
    $subtitle = esc_html( get_post_meta( $post_id, 'subtitle', true ) );
    return apply_filters( 'enable_subtitles_output', $subtitle, $post_id );
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

// Add subtitle support to post types
function enable_subtitle_support() {
    // Enable subtitle support for standard post types
    add_post_type_support( 'post', 'subtitle' );
    add_post_type_support( 'page', 'subtitle' );
}

// Automatically add subtitle support for all custom post types
function add_subtitle_to_custom_post_types( $args, $post_type ) {
    if ( isset( $args['supports'] ) && ! in_array( 'subtitle', $args['supports'] ) ) {
        $args['supports'][] = 'subtitle'; // Add subtitle support
    }
    return $args;
}

add_action( 'init', 'enable_subtitle_support' );
add_filter( 'register_post_type_args', 'add_subtitle_to_custom_post_types', 10, 2 );

// Admin functions
if ( is_admin() ) {
    // Add a meta box for subtitles
    function add_subtitle_meta_box() {
        add_meta_box( 'subtitle_meta_box', __( 'Subtitle', 'enable-subtitles' ), 'render_subtitle_meta_box', 'post', 'normal', 'high' );
        add_meta_box( 'subtitle_meta_box', __( 'Subtitle', 'enable-subtitles' ), 'render_subtitle_meta_box', 'page', 'normal', 'high' );
    }

    // Render the subtitle meta box
    function render_subtitle_meta_box( $post ) {
        wp_nonce_field( 'subtitle_nonce_action', 'subtitle_nonce' );
        $subtitle = get_post_meta( $post->ID, 'subtitle', true );
        echo '<label for="subtitle">' . __( 'Subtitle:', 'enable-subtitles' ) . '</label>';
        echo '<input type="text" id="subtitle" name="subtitle" value="' . esc_attr( $subtitle ) . '" style="width: 100%;" placeholder="' . esc_attr__( 'Enter subtitle here...', 'enable-subtitles' ) . '" />';
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
