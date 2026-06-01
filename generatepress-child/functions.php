<?php
if (!defined('ABSPATH')) {
    exit;
}

function techblog_enqueue_assets() {
    wp_enqueue_style(
        'techblog-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap',
        array(),
        null
    );

    if (is_front_page() || is_home()) {
        wp_enqueue_style(
            'techblog-hero-banner',
            get_stylesheet_directory_uri() . '/assets/css/hero-banner.css',
            array(),
            '1.0.2'
        );
    }

    wp_enqueue_style(
        'techblog-custom-style',
        get_stylesheet_directory_uri() . '/assets/css/custom.css',
        array(),
        '1.0.2'
    );

    wp_enqueue_script(
        'techblog-custom-script',
        get_stylesheet_directory_uri() . '/assets/js/custom.js',
        array('jquery'),
        '1.0.2',
        true
    );
}
add_action('wp_enqueue_scripts', 'techblog_enqueue_assets');

function techblog_homepage_no_sidebar($layout) {
    if (is_front_page() || is_home()) {
        return 'no-sidebar';
    }
    return $layout;
}
add_filter('generate_sidebar_layout', 'techblog_homepage_no_sidebar');

function techblog_homepage_full_width($classes) {
    if (is_front_page() || is_home()) {
        $classes[] = 'full-width-content';
        $classes[] = 'no-sidebar';
        $classes[] = 'homepage-hero';
    }
    return $classes;
}
add_filter('body_class', 'techblog_homepage_full_width');

function techblog_disable_sidebar_widgets($sidebars_widgets) {
    if (is_front_page() || is_home()) {
        $sidebars_widgets['sidebar-1'] = array();
        $sidebars_widgets['right-sidebar'] = array();
        $sidebars_widgets['left-sidebar'] = array();
    }
    return $sidebars_widgets;
}
add_filter('sidebars_widgets', 'techblog_disable_sidebar_widgets');

function techblog_set_full_width_content($value) {
    if (is_front_page() || is_home()) {
        return 'full-width';
    }
    return $value;
}
add_filter('generate_option_content_layout_setting', 'techblog_set_full_width_content');
