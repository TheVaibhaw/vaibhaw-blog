<?php
defined('ABSPATH') || exit;
get_header();
global $wp_query;
$total = $wp_query->found_posts;
?>
<div class="search-page">
    <div class="search-container">
        <header class="search-hero">
            <span class="search-hero__label">Search Results</span>
            <h1 class="search-hero__title">Results for: <span><?php echo get_search_query(); ?></span></h1>
            <p class="search-hero__desc"><?php printf(_n('%d article found', '%d articles found', $total, 'generatepress'), $total); ?></p>
            <form class="blog-search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <svg class="blog-search__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                <input class="blog-search__input" type="search" name="s" placeholder="Search articles..." value="<?php echo esc_attr(get_search_query()); ?>" aria-label="Search articles" />
                <button class="blog-search__btn" type="submit">Search</button>
            </form>
        </header>
        <?php if (have_posts()) : ?>
            <div class="blog-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <a href="<?php echo esc_url(get_permalink()); ?>" class="blog-card">
                        <div class="blog-card__thumb">
                            <?php if (has_post_thumbnail()) : the_post_thumbnail('medium_large', ['class' => 'blog-card__image', 'loading' => 'lazy']);
                            else : ?>
                                <div class="blog-card__placeholder"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z" />
                                        <circle cx="12" cy="13" r="3" />
                                    </svg></div>
                            <?php endif;
                            $cats = get_the_category();
                            if (!empty($cats)) : ?><span class="blog-card__cat"><?php echo esc_html($cats[0]->name); ?></span><?php endif; ?>
                        </div>
                        <div class="blog-card__body">
                            <h2 class="blog-card__title"><?php the_title(); ?></h2>
                            <p class="blog-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 18, '...')); ?></p>
                            <div class="blog-card__meta">
                                <time class="blog-card__date" datetime="<?php echo esc_attr(get_the_date('Y-m-d')); ?>"><?php echo esc_html(get_the_date('M j, Y')); ?></time>
                                <span class="blog-card__readtime"><?php echo max(1, ceil(str_word_count(wp_strip_all_tags(get_the_content())) / 200)); ?> min read</span>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
            <?php if ($wp_query->max_num_pages > 1) : ?>
                <div class="blog-pagination">
                    <?php echo paginate_links(['total' => $wp_query->max_num_pages, 'current' => max(1, get_query_var('paged')), 'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Previous', 'next_text' => 'Next <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>']); ?>
                </div>
            <?php endif;
        else : ?>
            <div class="blog-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                <h2>No Results Found</h2>
                <p>Sorry, no articles match your search for "<?php echo esc_html(get_search_query()); ?>".</p>
                <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="blog-empty__btn">Browse All Articles</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php get_footer(); ?>