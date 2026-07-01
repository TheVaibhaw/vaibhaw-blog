<?php
/**
 * Fetch all categories and post titles from blog.vaibhawkumar.in
 * using the WordPress REST API.
 *
 * Usage:
 *   CLI:     php list-posts.php
 *   Browser: open list-posts.php in your browser
 */

$site = 'https://blog.vaibhawkumar.in';

/**
 * Fetch all pages of a WP REST API endpoint (handles pagination).
 */
function wp_fetch_all($base_url) {
    $items = [];
    $page  = 1;

    do {
        $url = $base_url . (strpos($base_url, '?') === false ? '?' : '&')
             . 'per_page=100&page=' . $page;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_USERAGENT      => 'category-post-lister/1.0',
        ]);
        $response = curl_exec($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // 400 => page beyond the last one; stop cleanly.
        if ($status === 400 || $response === false) {
            break;
        }

        $data = json_decode($response, true);
        if (!is_array($data) || empty($data)) {
            break;
        }

        $items = array_merge($items, $data);
        $page++;
    } while (count($data) === 100);

    return $items;
}

// Detect whether we're running from the command line.
$is_cli = (php_sapi_name() === 'cli');
$nl     = $is_cli ? "\n" : "<br>\n";

if (!$is_cli) {
    header('Content-Type: text/html; charset=utf-8');
    echo "<pre>\n";
}

// 1. Fetch all categories.
$categories = wp_fetch_all("$site/wp-json/wp/v2/categories");

$cat_map = [];
foreach ($categories as $cat) {
    $cat_map[$cat['id']] = $cat['name'];
}

// 2. Fetch all posts.
$posts = wp_fetch_all("$site/wp-json/wp/v2/posts");

// 3. Group post titles by category.
$grouped = [];
foreach ($posts as $post) {
    $title = html_entity_decode($post['title']['rendered'], ENT_QUOTES, 'UTF-8');
    $cat_ids = !empty($post['categories']) ? $post['categories'] : [0];

    foreach ($cat_ids as $cid) {
        $cat_name = $cat_map[$cid] ?? 'Uncategorized';
        $grouped[$cat_name][] = $title;
    }
}

ksort($grouped);

// 4. Output the list.
echo "Categories and Posts from $site" . $nl;
echo str_repeat('=', 50) . $nl . $nl;

foreach ($grouped as $category => $titles) {
    echo "CATEGORY: $category (" . count($titles) . ")" . $nl;
    foreach ($titles as $title) {
        echo "   - $title" . $nl;
    }
    echo $nl;
}

echo "Total categories: " . count($grouped) . $nl;
echo "Total posts: " . count($posts) . $nl;

if (!$is_cli) {
    echo "</pre>\n";
}
