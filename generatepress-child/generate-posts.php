<?php
defined('ABSPATH') || require_once dirname(__FILE__, 5) . '/wp-load.php';

if (!current_user_can('publish_posts')) {
    wp_die('You need admin access to run this script.');
}

$json_file = __DIR__ . '/posts-data.json';

if (!file_exists($json_file)) {
    wp_die('posts-data.json file not found in theme directory.');
}

$json_data = file_get_contents($json_file);
$data = json_decode($json_data, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    wp_die('JSON Error: ' . json_last_error_msg());
}

if (empty($data['posts'])) {
    wp_die('No posts found in JSON file.');
}

$results = ['success' => [], 'failed' => [], 'skipped' => []];

foreach ($data['posts'] as $post_data) {
    $existing = get_page_by_path($post_data['slug'], OBJECT, 'post');
    if ($existing) {
        $results['skipped'][] = $post_data['title'] . ' (already exists)';
        continue;
    }

    $category_id = 0;
    if (!empty($post_data['category'])) {
        $cat = get_term_by('name', $post_data['category'], 'category');
        if ($cat) {
            $category_id = $cat->term_id;
        } else {
            $new_cat = wp_insert_term($post_data['category'], 'category');
            if (!is_wp_error($new_cat)) {
                $category_id = $new_cat['term_id'];
            }
        }
    }

    $post_args = [
        'post_title'   => sanitize_text_field($post_data['title']),
        'post_name'    => sanitize_title($post_data['slug']),
        'post_content' => wp_kses_post($post_data['content']),
        'post_excerpt' => sanitize_text_field($post_data['excerpt'] ?? ''),
        'post_status'  => $post_data['status'] ?? 'draft',
        'post_type'    => 'post',
        'post_author'  => get_current_user_id(),
    ];

    if ($category_id) {
        $post_args['post_category'] = [$category_id];
    }

    $post_id = wp_insert_post($post_args, true);

    if (is_wp_error($post_id)) {
        $results['failed'][] = $post_data['title'] . ' - ' . $post_id->get_error_message();
        continue;
    }

    if (!empty($post_data['tags'])) {
        wp_set_post_tags($post_id, $post_data['tags']);
    }

    if (!empty($post_data['meta_title'])) {
        update_post_meta($post_id, '_yoast_wpseo_title', sanitize_text_field($post_data['meta_title']));
        update_post_meta($post_id, '_seo_title', sanitize_text_field($post_data['meta_title']));
    }

    if (!empty($post_data['meta_description'])) {
        update_post_meta($post_id, '_yoast_wpseo_metadesc', sanitize_text_field($post_data['meta_description']));
        update_post_meta($post_id, '_seo_description', sanitize_text_field($post_data['meta_description']));
    }

    if (!empty($post_data['focus_keyword'])) {
        update_post_meta($post_id, '_yoast_wpseo_focuskw', sanitize_text_field($post_data['focus_keyword']));
        update_post_meta($post_id, '_seo_focus_keyword', sanitize_text_field($post_data['focus_keyword']));
    }

    $results['success'][] = $post_data['title'] . ' (ID: ' . $post_id . ')';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Generator Results</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            padding: 2rem;
            min-height: 100vh
        }

        .container {
            max-width: 800px;
            margin: 0 auto
        }

        h1 {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            color: #fff
        }

        .card {
            background: #1e293b;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem
        }

        .card h2 {
            font-size: 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem
        }

        .card ul {
            list-style: none;
            font-size: 0.875rem
        }

        .card li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #334155
        }

        .card li:last-child {
            border: none
        }

        .success h2 {
            color: #4ade80
        }

        .failed h2 {
            color: #f87171
        }

        .skipped h2 {
            color: #fbbf24
        }

        .count {
            background: #334155;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem
        }

        .empty {
            color: #64748b;
            font-style: italic
        }

        .actions {
            margin-top: 2rem;
            display: flex;
            gap: 1rem
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem
        }

        .btn-primary {
            background: #3b82f6;
            color: #fff
        }

        .btn-secondary {
            background: #334155;
            color: #e2e8f0
        }

        .note {
            background: #1e3a5f;
            border-left: 4px solid #3b82f6;
            padding: 1rem;
            margin-top: 1.5rem;
            border-radius: 0 8px 8px 0;
            font-size: 0.875rem
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Post Generator Results</h1>

        <div class="card success">
            <h2><span>✓</span> Created Successfully <span class="count"><?php echo count($results['success']); ?></span></h2>
            <?php if ($results['success']) : ?>
                <ul><?php foreach ($results['success'] as $item) : ?><li><?php echo esc_html($item); ?></li><?php endforeach; ?></ul>
            <?php else : ?>
                <p class="empty">No posts created</p>
            <?php endif; ?>
        </div>

        <div class="card skipped">
            <h2><span>⊘</span> Skipped (Already Exists) <span class="count"><?php echo count($results['skipped']); ?></span></h2>
            <?php if ($results['skipped']) : ?>
                <ul><?php foreach ($results['skipped'] as $item) : ?><li><?php echo esc_html($item); ?></li><?php endforeach; ?></ul>
            <?php else : ?>
                <p class="empty">No posts skipped</p>
            <?php endif; ?>
        </div>

        <div class="card failed">
            <h2><span>✕</span> Failed <span class="count"><?php echo count($results['failed']); ?></span></h2>
            <?php if ($results['failed']) : ?>
                <ul><?php foreach ($results['failed'] as $item) : ?><li><?php echo esc_html($item); ?></li><?php endforeach; ?></ul>
            <?php else : ?>
                <p class="empty">No failures</p>
            <?php endif; ?>
        </div>

        <div class="note">
            <strong>Next Steps:</strong> Go to Posts → All Posts in WordPress admin. Edit each post to add Featured Image. Posts are created as <strong>Draft</strong> - publish when ready.
        </div>

        <div class="actions">
            <a href="<?php echo admin_url('edit.php'); ?>" class="btn btn-primary">Go to Posts</a>
            <a href="<?php echo home_url(); ?>" class="btn btn-secondary">Back to Site</a>
        </div>
    </div>
</body>

</html>