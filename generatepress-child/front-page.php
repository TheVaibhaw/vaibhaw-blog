<?php get_header(); ?>

<section class="hero-banner" id="hero">
	<div class="hero-bg-particles" aria-hidden="true">
		<span class="particle"></span>
		<span class="particle"></span>
		<span class="particle"></span>
		<span class="particle"></span>
		<span class="particle"></span>
		<span class="particle"></span>
		<span class="particle"></span>
		<span class="particle"></span>
	</div>
	<div class="hero-grid-overlay" aria-hidden="true"></div>

	<div class="hero-container">
		<div class="hero-content">
			<div class="hero-badge" data-animate="fade-down">
				<span class="hero-badge__dot" aria-hidden="true"></span>
				<span class="hero-badge__text">Tech &bull; Code &bull; Insights</span>
			</div>

			<h1 class="hero-title" data-animate="fade-up">
				<span class="hero-title__line">Exploring the World of</span>
				<span class="hero-title__highlight">
					<span class="hero-title__typed" id="heroTyped" aria-live="polite"></span>
					<span class="hero-title__cursor" aria-hidden="true">|</span>
				</span>
			</h1>

			<p class="hero-description" data-animate="fade-up" data-delay="200">
				High-performance web development insights on React, Next.js, WordPress, and more. Written by
				<a href="https://vaibhawkumar.in" target="_blank" rel="noopener noreferrer">Vaibhaw</a>, a website developer at <strong class="text-white">Tech Prastish Mohali</strong> with roots in <strong class="text-white">Saran, Bihar</strong>. Professional software engineering solutions from Chapra to Mohali.
			</p>

			<div class="hero-actions" data-animate="fade-up" data-delay="400">
				<a href="<?php echo esc_url(home_url('/blog/')); ?>" class="hero-btn hero-btn--primary">
					<span>Browse Blog</span>
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M5 12h14" />
						<path d="m12 5 7 7-7 7" />
					</svg>
				</a>
			</div>
		</div>

		<div class="hero-visual" data-animate="fade-left">
			<div class="hero-image-wrapper">
				<img
					src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/hero-visual.png'); ?>"
					alt="Technology and programming illustration"
					class="hero-image"
					width="540"
					height="540"
					loading="eager"
					fetchpriority="high"
					decoding="async" />
			</div>
		</div>
	</div>

	<div class="hero-scroll-indicator" aria-hidden="true">
		<span class="hero-scroll-indicator__line"></span>
	</div>
</section>

<?php
$categories = get_categories(array(
	'orderby'    => 'count',
	'order'      => 'DESC',
	'hide_empty' => true,
));
if (! empty($categories)) : ?>
	<section class="categories-section" id="categories">
		<div class="categories-container">
			<div class="categories-header" data-animate="fade-up">
				<span class="categories-label">Browse Topics</span>
				<h2 class="categories-title">Explore Categories</h2>
				<p class="categories-subtitle">Find articles on the topics that interest you most</p>
			</div>
			<div class="categories-slider-wrapper" data-animate="fade-up" data-delay="200">
				<button class="categories-nav categories-nav--prev" aria-label="Previous categories" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round">
						<path d="m15 18-6-6 6-6" />
					</svg>
				</button>
				<div class="categories-slider" id="categoriesSlider">
					<div class="categories-track">
						<?php foreach ($categories as $index => $category) :
							$cat_link  = esc_url(get_category_link($category->term_id));
							$cat_name  = esc_html($category->name);
							$cat_count = $category->count;
							$cat_desc  = esc_html($category->description);
							$icons = array(
								'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>',
								'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>',
								'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
								'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
								'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>',
								'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
							);
							$icon = $icons[$index % count($icons)];
						?>
							<a href="<?php echo $cat_link; ?>" class="category-card">
								<div class="category-card__icon"><?php echo $icon; ?></div>
								<h3 class="category-card__name"><?php echo $cat_name; ?></h3>
								<?php if ($cat_desc) : ?>
									<p class="category-card__desc"><?php echo $cat_desc; ?></p>
								<?php endif; ?>
								<span class="category-card__count"><?php echo $cat_count; ?> <?php echo $cat_count === 1 ? 'Article' : 'Articles'; ?></span>
								<span class="category-card__arrow">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M5 12h14" />
										<path d="m12 5 7 7-7 7" />
									</svg>
								</span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
				<button class="categories-nav categories-nav--next" aria-label="Next categories" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round">
						<path d="m9 18 6-6-6-6" />
					</svg>
				</button>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
$latest_posts = new WP_Query(array(
	'posts_per_page' => 5,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
));
if ($latest_posts->have_posts()) : ?>
	<section class="latest-posts-section" id="latest-posts">
		<div class="latest-posts-container">
			<div class="latest-posts-header" data-animate="fade-up">
				<span class="latest-posts-label">Recently Published</span>
				<h2 class="latest-posts-title">Latest Articles</h2>
				<p class="latest-posts-subtitle">Stay updated with the newest content from the blog</p>
			</div>
			<div class="latest-posts-grid" data-animate="fade-up" data-delay="200">
				<?php while ($latest_posts->have_posts()) : $latest_posts->the_post(); ?>
					<a href="<?php echo esc_url(get_permalink()); ?>" class="post-card">
						<div class="post-card__thumbnail">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('medium_large', array('class' => 'post-card__image', 'loading' => 'lazy')); ?>
							<?php else : ?>
								<div class="post-card__placeholder">
									<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
										<path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z" />
										<circle cx="12" cy="13" r="3" />
									</svg>
								</div>
							<?php endif; ?>
							<?php
							$post_categories = get_the_category();
							if (! empty($post_categories)) : ?>
								<span class="post-card__category"><?php echo esc_html($post_categories[0]->name); ?></span>
							<?php endif; ?>
						</div>
						<div class="post-card__body">
							<h3 class="post-card__title"><?php the_title(); ?></h3>
							<p class="post-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 16, '...')); ?></p>
							<div class="post-card__meta">
								<time class="post-card__date" datetime="<?php echo esc_attr(get_the_date('Y-m-d')); ?>"><?php echo esc_html(get_the_date('M j, Y')); ?></time>
								<span class="post-card__readtime"><?php echo max(1, ceil(str_word_count(get_the_content()) / 200)); ?> min read</span>
							</div>
						</div>
					</a>
				<?php endwhile; ?>
			</div>
			<div class="latest-posts-cta" data-animate="fade-up" data-delay="400">
				<a href="<?php echo esc_url(home_url('/blog/')); ?>" class="latest-posts-btn">
					<span>View All Articles</span>
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M5 12h14" />
						<path d="m12 5 7 7-7 7" />
					</svg>
				</a>
			</div>
		</div>
	</section>
<?php endif;
wp_reset_postdata(); ?>

<?php get_footer(); ?>