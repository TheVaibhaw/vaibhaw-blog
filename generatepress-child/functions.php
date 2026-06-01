<?php
if (!defined('ABSPATH')) {
exit;
}

function techblog_enqueue_assets()
{
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
'1.0.1'
);
}

wp_enqueue_style(
'techblog-custom-style',
get_stylesheet_directory_uri() . '/assets/css/custom.css',
array(),
'1.0.1'
);

wp_enqueue_script(
'techblog-custom-script',
get_stylesheet_directory_uri() . '/assets/js/custom.js',
array('jquery'),
'1.0.1',
true
);
}
add_action('wp_enqueue_scripts', 'techblog_enqueue_assets');

function techblog_homepage_no_sidebar($layout)
{
if (is_front_page() || is_home()) {
return 'no-sidebar';
}
return $layout;
}
add_filter('generate_sidebar_layout', 'techblog_homepage_no_sidebar');

function techblog_homepage_full_width($classes)
{
if (is_front_page() || is_home()) {
$classes[] = 'full-width-content';
$classes[] = 'no-sidebar';
}
return $classes;
}
add_filter('body_class', 'techblog_homepage_full_width');
