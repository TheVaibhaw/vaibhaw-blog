<?php
defined('ABSPATH') || exit;
get_header();

$author    = get_queried_object();
$author_id = $author->ID;
?>

<div class="author-page">
	<div class="author-container">
		<div class="author-profile" data-animate="fade-up">
			<div class="author-profile__avatar">
				<?php echo get_avatar($author_id, 120, '', $author->display_name, ['class' => 'author-profile__img']); ?>
			</div>
			<div class="author-profile__info">
				<span class="author-profile__label">Author</span>
				<h1 class="author-profile__name"><?php echo esc_html($author->display_name); ?></h1>
				<?php if ($author->description) : ?>
					<p class="author-profile__bio"><?php echo wp_kses_post($author->description); ?></p>
				<?php endif; ?>
				<div class="author-profile__stats">
					<span class="author-profile__stat">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z" />
							<circle cx="12" cy="13" r="3" />
						</svg>
						<?php echo count_user_posts($author_id); ?> Articles
					</span>
					<?php if ($author->user_url) : ?>
						<a href="<?php echo esc_url($author->user_url); ?>" class="author-profile__stat author-profile__link" target="_blank" rel="noopener">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
								<path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
							</svg>
							Website
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="author-posts-header" data-animate="fade-up" data-delay="200">
			<h2 class="author-posts-header__title">Articles by <?php echo esc_html($author->display_name); ?></h2>
		</div>

		<?php if (have_posts()) : ?>
			<div class="blog-grid" data-animate="fade-up" data-delay="300">
				<?php while (have_posts()) : the_post(); ?>
					<a href="<?php echo esc_url(get_permalink()); ?>" class="blog-card">
						<div class="blog-card__thumb">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('medium_large', ['class' => 'blog-card__image', 'loading' => 'lazy']); ?>
							<?php else : ?>
								<div class="blog-card__placeholder">
									<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
										<path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z" />
										<circle cx="12" cy="13" r="3" />
									</svg>
								</div>
							<?php endif; ?>
							<?php $cats = get_the_category();
							if (!empty($cats)) : ?>
								<span class="blog-card__cat"><?php echo esc_html($cats[0]->name); ?></span>
							<?php endif; ?>
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

			<div class="blog-pagination">
				<?php
				the_posts_pagination([
					'mid_size'  => 2,
					'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Previous',
					'next_text' => 'Next <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>',
				]);
				?>
			</div>

		<?php else : ?>
			<div class="blog-empty">
				<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="11" cy="11" r="8" />
					<path d="m21 21-4.3-4.3" />
				</svg>
				<h2>No Articles Found</h2>
				<p>This author hasn't published any articles yet.</p>
				<a href="<?php echo esc_url(home_url('/')); ?>" class="blog-empty__btn">Back to Home</a>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>