<?php 

function enqueue_custom_styles() {
	wp_enqueue_style('custom-style', get_stylesheet_directory_uri() . '/assets/css/custom.css');
	wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_styles');