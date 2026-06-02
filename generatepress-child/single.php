<?php

/**
 * Single Post Template with Sidebar
 *
 * @package GeneratePress Child
 */

defined('ABSPATH') || exit;

get_header();
?>

<div class="single-post-layout">
    <div class="single-post-container">
        <!-- Main Content -->
        <main class="single-post-main">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('single-article'); ?>>
                    <div class="single-article__inner">
                        <!-- Article Header -->
                        <header class="single-article__header">
                            <?php $cats = get_the_category();
                            if (!empty($cats)) : ?>
                                <a href="<?php echo esc_url(get_category_link($cats[0]->term_id)); ?>" class="single-article__cat">
                                    <?php echo esc_html($cats[0]->name); ?>
                                </a>
                            <?php endif; ?>

                            <h1 class="single-article__title"><?php the_title(); ?></h1>

                            <div class="single-article__meta">
                                <div class="single-article__author">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 36, '', '', ['class' => 'single-article__avatar']); ?>
                                    <span class="single-article__author-name"><?php the_author(); ?></span>
                                </div>
                                <span class="single-article__separator">·</span>
                                <time class="single-article__date" datetime="<?php echo esc_attr(get_the_date('Y-m-d')); ?>">
                                    <?php echo esc_html(get_the_date('M j, Y')); ?>
                                </time>
                                <span class="single-article__separator">·</span>
                                <span class="single-article__readtime">
                                    <?php echo max(1, ceil(str_word_count(wp_strip_all_tags(get_the_content())) / 200)); ?> min read
                                </span>
                            </div>
                        </header>

                        <!-- Featured Image -->
                        <?php if (has_post_thumbnail()) : ?>
                            <figure class="single-article__featured">
                                <?php the_post_thumbnail('large', ['class' => 'single-article__image']); ?>
                            </figure>
                        <?php endif; ?>

                        <!-- Article Content -->
                        <div class="single-article__content entry-content">
                            <?php the_content(); ?>
                        </div>

                        <!-- Tags -->
                        <?php $tags = get_the_tags();
                        if ($tags) : ?>
                            <footer class="single-article__footer">
                                <div class="single-article__tags">
                                    <span class="single-article__tags-label">Tags:</span>
                                    <?php foreach ($tags as $tag) : ?>
                                        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="single-article__tag">
                                            <?php echo esc_html($tag->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </footer>
                        <?php endif; ?>

                        <!-- Post Navigation -->
                        <nav class="single-post-nav">
                            <?php
                            $prev_post = get_previous_post();
                            $next_post = get_next_post();
                            ?>
                            <?php if ($prev_post) : ?>
                                <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" class="single-post-nav__link single-post-nav__prev">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m15 18-6-6 6-6" />
                                    </svg>
                                    <span>Prev</span>
                                </a>
                            <?php endif; ?>

                            <?php if ($next_post) : ?>
                                <a href="<?php echo esc_url(get_permalink($next_post)); ?>" class="single-post-nav__link single-post-nav__next">
                                    <span>Next</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                </article>

                <!-- Comments -->
                <?php if (comments_open() || get_comments_number()) : ?>
                    <div class="single-comments">
                        <?php comments_template(); ?>
                    </div>
                <?php endif; ?>

            <?php endwhile; ?>
        </main>

        <!-- Sidebar -->
        <aside class="single-sidebar">
            <div class="single-sidebar__inner">
                <!-- Latest Posts Widget -->
                <div class="sidebar-widget latest-posts-widget">
                    <h3 class="sidebar-widget__title">Latest Posts</h3>
                    <ul class="sidebar-latest-posts">
                        <?php
                        $latest_posts = new WP_Query([
                            'post_type'      => 'post',
                            'post_status'    => 'publish',
                            'posts_per_page' => 5,
                            'post__not_in'   => [get_the_ID()],
                            'orderby'        => 'date',
                            'order'          => 'DESC',
                        ]);

                        if ($latest_posts->have_posts()) :
                            while ($latest_posts->have_posts()) : $latest_posts->the_post();
                        ?>
                                <li class="sidebar-latest-posts__item">
                                    <a href="<?php echo esc_url(get_permalink()); ?>" class="sidebar-latest-posts__link">
                                        <?php the_title(); ?>
                                    </a>
                                    <time class="sidebar-latest-posts__date" datetime="<?php echo esc_attr(get_the_date('Y-m-d')); ?>">
                                        <?php echo esc_html(get_the_date('M j, Y')); ?>
                                    </time>
                                </li>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </ul>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php get_footer(); ?>