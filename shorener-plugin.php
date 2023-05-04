<?php
/**
 * Plugin Name: Test task
 * Description: A plugin for generating shortened links in WordPress using AJAX
 * Version: 1.0.0
 * Author: Zaloha Denys
 */

// Enqueue plugin assets
function my_shortener_enqueue_assets() {
    wp_enqueue_script( 'my-shortener', plugin_dir_url(__FILE__) . 'js/shorten-link.js', array('jquery'), '1.0.0', true );
    wp_localize_script( 'my-shortener', 'my_shortener', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('shorten_link_nonce')
    ));
}

add_action('wp_enqueue_scripts', 'my_shortener_enqueue_assets');

// Generate shortened link
function my_shortener_generate_short_link() {
    $nonce = $_POST[ 'nonce' ];
    if ( !wp_verify_nonce($nonce, 'shorten_link_nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    $link = $_POST['link'];

    // Generate a unique slug for the shortened link
    $slug = wp_generate_password( 6, false );

    // Create a new post of a custom post type to store the shortened link
    $post_id = wp_insert_post( array(
        'post_title'   => $slug,
        'post_content' => $link,
        'post_status'  => 'publish',
        'post_type'    => 'shortened_link'
    ) );

    // Build the shortened link URL based on the post ID and slug
    $shortened_link = get_permalink( $post_id) . $slug;

    wp_send_json_success( array( 'shortened_link' => $shortened_link ) );
}

add_action( 'wp_ajax_my_shortener_generate_short_link', 'my_shortener_generate_short_link' );
add_action( 'wp_ajax_nopriv_my_shortener_generate_short_link', 'my_shortener_generate_short_link' );
