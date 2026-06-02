<?php
defined('ABSPATH') || exit;

function generatepress_child_resource_hints($urls, $relation_type)
{
  if ('preconnect' === $relation_type) {
    $urls[] = ['href' => 'https://fonts.googleapis.com', 'crossorigin' => ''];
    $urls[] = ['href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous'];
  }
  if ('dns-prefetch' === $relation_type) {
    $urls[] = 'https://fonts.googleapis.com';
    $urls[] = 'https://fonts.gstatic.com';
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
  $defer_handles = ['generatepress-child-custom-script', 'comment-reply'];
  if (in_array($handle, $defer_handles, true)) {
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
  add_filter('tiny_mce_plugins', function ($plugins) {
    return is_array($plugins) ? array_diff($plugins, ['wpemoji']) : [];
  });
  add_filter('wp_resource_hints', function ($urls, $relation_type) {
    if ('dns-prefetch' === $relation_type) {
      $urls = array_filter($urls, function ($url) {
        return strpos($url, 'https://s.w.org/images/core/emoji/') === false;
      });
    }
    return $urls;
  }, 10, 2);
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

function generatepress_child_cleanup_head()
{
  remove_action('wp_head', 'rsd_link');
  remove_action('wp_head', 'wlwmanifest_link');
  remove_action('wp_head', 'wp_generator');
  remove_action('wp_head', 'wp_shortlink_wp_head');
  remove_action('wp_head', 'rest_output_link_wp_head');
  remove_action('wp_head', 'wp_oembed_add_discovery_links');
  remove_action('wp_head', 'wp_oembed_add_host_js');
  remove_action('wp_head', 'feed_links_extra', 3);
}
add_action('after_setup_theme', 'generatepress_child_cleanup_head');

add_filter('xmlrpc_enabled', '__return_false');

function generatepress_child_remove_query_strings($src)
{
  if (strpos($src, '?ver=') !== false) {
    $src = remove_query_arg('ver', $src);
  }
  return $src;
}
add_filter('style_loader_src', 'generatepress_child_remove_query_strings', 10);
add_filter('script_loader_src', 'generatepress_child_remove_query_strings', 10);

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
  $site_name = get_bloginfo('name');

  if (is_single()) {
    $title = get_the_title() . ' | ' . $site_name;
    $description = wp_trim_words(get_the_excerpt(), 30, '...');
    $og_type = 'article';
    $og_image = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: '';
    $keywords = '';
    $tags = get_the_tags();
    if ($tags) {
      $tag_names = array_map(function ($tag) {
        return $tag->name;
      }, $tags);
      $keywords = implode(', ', $tag_names);
    }
  } elseif (is_category()) {
    $cat = get_queried_object();
    $title = $cat->name . ' - Articles | ' . $site_name;
    $description = $cat->description ?: 'Browse articles in ' . $cat->name . ' category';
    $og_type = 'website';
    $og_image = '';
    $keywords = $cat->name . ', ' . $site_name;
  } elseif (is_search()) {
    $title = 'Search: ' . get_search_query() . ' | ' . $site_name;
    $description = 'Search results for "' . get_search_query() . '" on ' . $site_name;
    $og_type = 'website';
    $og_image = '';
    $keywords = get_search_query();
  } else {
    $title = $site_name . ' | Tech & Code Insights';
    $description = 'Vaibhaw Kumar (Vaibhaw Parashar) - Official Blog. Deep dives into web development, React, Next.js, WordPress, and tech insights.';
    $og_type = 'website';
    $og_image = '';
    $keywords = 'Vaibhaw Kumar, Web Developer, WordPress, React, Next.js, Tech Blog';
  }
?>
  <meta name="google-site-verification" content="maW-hJdQ9AJySI8xUreMqnx8hi79D-N356K_k1qL4MU" />
  <meta name="description" content="<?php echo esc_attr($description); ?>" />
  <?php if ($keywords) : ?>
    <meta name="keywords" content="<?php echo esc_attr($keywords); ?>" />
  <?php endif; ?>
  <link rel="canonical" href="<?php echo $canonical; ?>" />

  <meta property="og:type" content="<?php echo esc_attr($og_type); ?>" />
  <meta property="og:title" content="<?php echo esc_attr($title); ?>" />
  <meta property="og:description" content="<?php echo esc_attr($description); ?>" />
  <meta property="og:url" content="<?php echo $canonical; ?>" />
  <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>" />
  <?php if ($og_image) : ?>
    <meta property="og:image" content="<?php echo esc_url($og_image); ?>" />
  <?php endif; ?>

  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?php echo esc_attr($title); ?>" />
  <meta name="twitter:description" content="<?php echo esc_attr($description); ?>" />
  <?php if ($og_image) : ?>
    <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>" />
  <?php endif; ?>

  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
  <meta name="author" content="Vaibhaw Kumar Parashar" />

  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [{
          "@type": "WebSite",
          "name": "<?php echo esc_js($site_name); ?>",
          "url": "<?php echo $home_url; ?>",
          "description": "Tech and programming blog by Vaibhaw Kumar Parashar",
          "potentialAction": {
            "@type": "SearchAction",
            "target": "<?php echo $home_url; ?>?s={search_term_string}",
            "query-input": "required name=search_term_string"
          }
        },
        {
          "@type": "Person",
          "name": "Vaibhaw Kumar Parashar",
          "url": "https://vaibhawkumarparashar.in",
          "sameAs": [
            "https://linkedin.com/in/itsvaibhaw/",
            "https://github.com/thevaibhaw"
          ],
          "jobTitle": "Web Developer",
          "worksFor": {
            "@type": "Organization",
            "name": "Tech Prastish"
          }
        }
        <?php if (is_single()) : ?>, {
            "@type": "BlogPosting",
            "headline": "<?php echo esc_js(get_the_title()); ?>",
            "description": "<?php echo esc_js($description); ?>",
            "url": "<?php echo esc_url(get_permalink()); ?>",
            "datePublished": "<?php echo get_the_date('c'); ?>",
            "dateModified": "<?php echo get_the_modified_date('c'); ?>",
            "author": {
              "@type": "Person",
              "name": "<?php echo esc_js(get_the_author()); ?>"
            },
            "publisher": {
              "@type": "Organization",
              "name": "<?php echo esc_js($site_name); ?>"
            }
            <?php if ($og_image) : ?>,
              "image": "<?php echo esc_url($og_image); ?>"
            <?php endif; ?>
          }
        <?php endif; ?>
      ]
    }
  </script>
<?php
}
add_action('wp_head', 'generatepress_child_seo_metadata', 1);

function generatepress_child_post_navigation_args($args)
{
  $args['previous_format'] = '<div class="nav-previous">' . generate_get_svg_icon('arrow-left') . '<span class="prev">%link</span></div>';
  $args['next_format'] = '<div class="nav-next"><span class="next">%link</span>' . generate_get_svg_icon('arrow-right') . '</div>';
  $args['link'] = 'Prev';
  return $args;
}
add_filter('generate_post_navigation_args', 'generatepress_child_post_navigation_args');

function generatepress_child_next_post_link_text($link)
{
  return str_replace('Prev', 'Next', $link);
}
add_filter('next_post_link', 'generatepress_child_next_post_link_text');
