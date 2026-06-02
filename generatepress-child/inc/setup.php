<?php
defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('generatepress-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('generatepress-child-style', get_stylesheet_directory_uri() . '/assets/css/style.css', ['generatepress-style'], wp_get_theme()->get('Version'));
    wp_enqueue_script('generatepress-child-script', get_stylesheet_directory_uri() . '/assets/js/script.js', [], wp_get_theme()->get('Version'), ['strategy' => 'defer', 'in_footer' => true]);
}, 20);

add_filter('generate_copyright', function () {
    return '&copy; ' . date('Y') . ' ' . get_bloginfo('name');
});

add_action('after_switch_theme', function () {
    if (!get_page_by_path('blog')) {
        $page_id = wp_insert_post(['post_title' => 'Blog', 'post_name' => 'blog', 'post_status' => 'publish', 'post_type' => 'page', 'post_content' => '']);
        if ($page_id && !is_wp_error($page_id)) {
            update_post_meta($page_id, '_wp_page_template', 'page-templates/blog.php');
        }
    }
});

add_action('init', function () {
    if (!get_page_by_path('blog')) {
        if (!get_page_by_path('blog')) {
            $page_id = wp_insert_post(['post_title' => 'Blog', 'post_name' => 'blog', 'post_status' => 'publish', 'post_type' => 'page', 'post_content' => '']);
            if ($page_id && !is_wp_error($page_id)) {
                update_post_meta($page_id, '_wp_page_template', 'page-templates/blog.php');
            }
        }
    }
});

add_filter('generate_post_navigation_args', function ($args) {
    $args['previous_format'] = '<div class="nav-previous">' . generate_get_svg_icon('arrow-left') . '<span class="prev">%link</span></div>';
    $args['next_format'] = '<div class="nav-next"><span class="next">%link</span>' . generate_get_svg_icon('arrow-right') . '</div>';
    $args['link'] = 'Prev';
    return $args;
});

add_filter('next_post_link', function ($link) {
    return str_replace('Prev', 'Next', $link);
});
