<?php
defined('ABSPATH') || exit;

add_action('wp_head', function () {
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
        <meta name="keywords" content="<?php echo esc_attr($keywords); ?>" /><?php endif; ?>
    <link rel="canonical" href="<?php echo $canonical; ?>" />
    <meta property="og:type" content="<?php echo esc_attr($og_type); ?>" />
    <meta property="og:title" content="<?php echo esc_attr($title); ?>" />
    <meta property="og:description" content="<?php echo esc_attr($description); ?>" />
    <meta property="og:url" content="<?php echo $canonical; ?>" />
    <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>" />
    <?php if ($og_image) : ?>
        <meta property="og:image" content="<?php echo esc_url($og_image); ?>" /><?php endif; ?>
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>" />
    <?php if ($og_image) : ?>
        <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>" /><?php endif; ?>
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
}, 1);
