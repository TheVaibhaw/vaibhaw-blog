<?php
defined('ABSPATH') || exit;

function generatepress_child_resource_hints($urls, $relation_type)
{
  if ('preconnect' === $relation_type) {
    $urls[] = ['href' => 'https://fonts.googleapis.com', 'crossorigin' => ''];
    $urls[] = ['href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous'];
  }
  return $urls;
}
add_filter('wp_resource_hints', 'generatepress_child_resource_hints', 10, 2);

function generatepress_child_preload_fonts()
{
  echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
  echo '<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"></noscript>';
}
add_action('wp_head', 'generatepress_child_preload_fonts', 1);

function generatepress_child_enqueue_scripts()
{
  wp_enqueue_style('generatepress-style', get_template_directory_uri() . '/style.css');
  wp_enqueue_style('generatepress-child-custom-style', get_stylesheet_directory_uri() . '/assets/css/style.css', ['generatepress-style'], wp_get_theme()->get('Version'));
  wp_enqueue_script('generatepress-child-custom-script', get_stylesheet_directory_uri() . '/assets/js/script.js', [], wp_get_theme()->get('Version'), ['strategy' => 'defer', 'in_footer' => true]);
}
add_action('wp_enqueue_scripts', 'generatepress_child_enqueue_scripts', 20);

function generatepress_child_defer_scripts($tag, $handle, $src)
{
  if (in_array($handle, ['generatepress-child-custom-script'], true)) {
    return str_replace(' src', ' defer src', $tag);
  }
  return $tag;
}
add_filter('script_loader_tag', 'generatepress_child_defer_scripts', 10, 3);

function generatepress_child_disable_emojis()
{
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_action('admin_print_styles', 'print_emoji_styles');
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'generatepress_child_disable_emojis');

function generatepress_child_remove_jquery_migrate($scripts)
{
  if (!is_admin() && isset($scripts->registered['jquery']) && $scripts->registered['jquery']->deps) {
    $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, ['jquery-migrate']);
  }
}
add_action('wp_default_scripts', 'generatepress_child_remove_jquery_migrate');

function generatepress_child_lazy_load_iframes($content)
{
  return str_replace('<iframe', '<iframe loading="lazy"', $content);
}
add_filter('the_content', 'generatepress_child_lazy_load_iframes');

function generatepress_child_remove_footer_credit($credits)
{
  return '&copy; ' . date('Y') . ' ' . get_bloginfo('name');
}
add_filter('generate_copyright', 'generatepress_child_remove_footer_credit');

function generatepress_child_create_blog_page()
{
  if (!get_page_by_path('blog')) {
    $page_id = wp_insert_post(['post_title' => 'Blog', 'post_name' => 'blog', 'post_status' => 'publish', 'post_type' => 'page', 'post_content' => '']);
    if ($page_id && !is_wp_error($page_id)) {
      update_post_meta($page_id, '_wp_page_template', 'page-templates/blog.php');
    }
  }
}
add_action('after_switch_theme', 'generatepress_child_create_blog_page');

function generatepress_child_ensure_blog_page()
{
  if (!get_page_by_path('blog')) {
    generatepress_child_create_blog_page();
  }
}
add_action('init', 'generatepress_child_ensure_blog_page');

function generatepress_child_seo_metadata()
{
  $home_url = esc_url(home_url('/'));
  $canonical = esc_url(home_url(add_query_arg(null, null)));
?>
  <meta name="google-site-verification" content="maW-hJdQ9AJySI8xUreMqnx8hi79D-N356K_k1qL4MU" />
  <meta name="description" content="Vaibhaw Kumar (Vaibhaw Parashar) - Official Blog. Deep dives into web development, React, Next.js, WordPress, and tech insights. Best web developer in Mohali and Saran, Bihar." />
  <meta name="keywords" content="Vaibhaw Kumar, Vaibhaw Kumar Parashar, Vaibhaw Parashar, Tech Prastish Vaibhaw Kumar, Vaibhaw Mohali, Vaibhaw Kumar Mohali, Vaibhaw Kumar Bihar, Vaibhaw Kumar Chapra, Web Developer, Software Engineer, WordPress Expert" />
  <link rel="canonical" href="<?php echo $canonical; ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="Vaibhaw Kumar | Tech & Code Insights" />
  <meta property="og:description" content="Official Blog of Vaibhaw Kumar Parashar. Expertise in Web Development, Software Engineering, and Digital Solutions." />
  <meta property="og:url" content="<?php echo $home_url; ?>" />
  <meta property="og:site_name" content="Vaibhaw Kumar" />
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [{
        "@type": "WebSite",
        "name": "Vaibhaw Kumar Blog",
        "url": "<?php echo $home_url; ?>",
        "description": "Tech and programming blog by Vaibhaw Kumar Parashar"
      }, {
        "@type": "Person",
        "name": "Vaibhaw Kumar Parashar",
        "url": "https://vaibhawkumarparashar.in",
        "sameAs": ["https://linkedin.com/in/itsvaibhaw/", "https://github.com/thevaibhaw"],
        "jobTitle": "Web Developer",
        "worksFor": {
          "@type": "Organization",
          "name": "Tech Prastish"
        }
      }]
    }
  </script>
<?php
}
add_action('wp_head', 'generatepress_child_seo_metadata', 1);
