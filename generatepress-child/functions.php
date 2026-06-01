<?php

/**
 * GeneratePress Child Theme Functions
 * 
 * @package GeneratePress Child
 * @author Vaibhaw Kumar
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * Enqueue Google Fonts, Custom Styles and Scripts
 */
function techblog_enqueue_assets()
{
	// Google Fonts - Inter (UI) + JetBrains Mono (Code)
	wp_enqueue_style(
		'techblog-google-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	// Hero Banner CSS (only on front page)
	if (is_front_page() || is_home()) {
		wp_enqueue_style(
			'techblog-hero-banner',
			get_stylesheet_directory_uri() . '/assets/css/hero-banner.css',
			array(),
			'1.0.1'
		);
	}

	// Main Custom CSS
	wp_enqueue_style(
		'techblog-custom-style',
		get_stylesheet_directory_uri() . '/assets/css/custom.css',
		array(),
		'1.0.1'
	);

	// Custom JavaScript
	wp_enqueue_script(
		'techblog-custom-script',
		get_stylesheet_directory_uri() . '/assets/js/custom.js',
		array('jquery'),
		'1.0.1',
		true
	);
}
add_action('wp_enqueue_scripts', 'techblog_enqueue_assets');
