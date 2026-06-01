<?php
/**
 * Template Name: Blog Page
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$blog_query = new WP_Query( array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 10,
	'paged'          => $paged,
	'orderby'        => 'date',
	'order'          => 'DESC',
) );
?>

<div class="blog-page">
	<div class="blog-container">
		<header class="blog-hero" data-animate="fade-up">
			<span class="blog-hero__label">Our Blog</span>
			<h1 class="blog-hero__title">All Articles</h1>
			<p class="blog-hero__desc">Explore in-depth articles on web technologies, programming, tools, and developer insights</p>
			<form class="blog-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<svg class="blog-search__icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
				<input class="blog-search__input" type="search" name="s" placeholder="Search articles..." value="<?php echo esc_attr( get_search_query() ); ?>" aria-label="Search articles" />
				<button class="blog-search__btn" type="submit">Search</button>
			</form>
		</header>

		<?php if ( $blog_query->have_posts() ) : ?>
		<div class="blog-grid" data-animate="fade-up" data-delay="200">
			<?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="blog-card">
				<div class="blog-card__thumb">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'medium_large', array( 'class' => 'blog-card__image', 'loading' => 'lazy' ) ); ?>
					<?php else : ?>
						<div class="blog-card__placeholder">
							<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
						</div>
					<?php endif; ?>
					<?php
					$cats = get_the_category();
					if ( ! empty( $cats ) ) : ?>
						<span class="blog-card__cat"><?php echo esc_html( $cats[0]->name ); ?></span>
					<?php endif; ?>
				</div>
				<div class="blog-card__body">
					<h2 class="blog-card__title"><?php the_title(); ?></h2>
					<p class="blog-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18, '...' ) ); ?></p>
					<div class="blog-card__meta">
						<time class="blog-card__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></time>
						<span class="blog-card__readtime"><?php echo max( 1, ceil( str_word_count( wp_strip_all_tags( get_the_content() ) ) / 200 ) ); ?> min read</span>
					</div>
				</div>
			</a>
			<?php endwhile; ?>
		</div>

		<?php if ( $blog_query->max_num_pages > 1 ) : ?>
		<div class="blog-pagination" data-animate="fade-up" data-delay="300">
			<?php
			echo paginate_links( array(
				'total'     => $blog_query->max_num_pages,
				'current'   => $paged,
				'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Previous',
				'next_text' => 'Next <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>',
			) );
			?>
		</div>
		<?php endif; ?>

		<?php else : ?>
		<div class="blog-empty">
			<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
			<h2>No Articles Yet</h2>
			<p>Check back soon for new content.</p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="blog-empty__btn">Back to Home</a>
		</div>
		<?php endif; wp_reset_postdata(); ?>
	</div>
</div>

<?php get_footer(); ?>
