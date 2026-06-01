<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

<div class="archive-page">
	<div class="archive-container">
		<?php if ( have_posts() ) : ?>
		<header class="archive-hero" data-animate="fade-up">
			<?php
			$archive_title = '';
			$archive_desc  = '';
			if ( is_category() ) {
				$archive_title = single_cat_title( '', false );
				$archive_desc  = category_description();
			} elseif ( is_tag() ) {
				$archive_title = single_tag_title( '', false );
				$archive_desc  = tag_description();
			} elseif ( is_author() ) {
				$archive_title = get_the_author();
			} elseif ( is_date() ) {
				if ( is_year() ) {
					$archive_title = get_the_date( 'Y' );
				} elseif ( is_month() ) {
					$archive_title = get_the_date( 'F Y' );
				} else {
					$archive_title = get_the_date();
				}
			} else {
				$archive_title = post_type_archive_title( '', false );
			}
			?>
			<span class="archive-hero__label"><?php echo esc_html( is_category() ? 'Category' : ( is_tag() ? 'Tag' : 'Archive' ) ); ?></span>
			<h1 class="archive-hero__title"><?php echo esc_html( $archive_title ); ?></h1>
			<?php if ( $archive_desc ) : ?>
				<p class="archive-hero__desc"><?php echo wp_kses_post( $archive_desc ); ?></p>
			<?php endif; ?>
			<span class="archive-hero__count"><?php echo esc_html( $wp_query->found_posts ); ?> <?php echo $wp_query->found_posts === 1 ? 'Article' : 'Articles'; ?></span>
		</header>

		<div class="archive-list" data-animate="fade-up" data-delay="200">
			<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class( 'archive-item' ); ?>>
				<a href="<?php echo esc_url( get_permalink() ); ?>" class="archive-item__link">
					<div class="archive-item__thumb">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'medium', array( 'class' => 'archive-item__image', 'loading' => 'lazy' ) ); ?>
						<?php else : ?>
							<div class="archive-item__placeholder">
								<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
							</div>
						<?php endif; ?>
					</div>
					<div class="archive-item__body">
						<div class="archive-item__meta">
							<?php
							$cats = get_the_category();
							if ( ! empty( $cats ) ) : ?>
								<span class="archive-item__cat"><?php echo esc_html( $cats[0]->name ); ?></span>
							<?php endif; ?>
							<time class="archive-item__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></time>
						</div>
						<h2 class="archive-item__title"><?php the_title(); ?></h2>
						<p class="archive-item__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24, '...' ) ); ?></p>
						<div class="archive-item__footer">
							<span class="archive-item__readtime"><?php echo max( 1, ceil( str_word_count( wp_strip_all_tags( get_the_content() ) ) / 200 ) ); ?> min read</span>
							<span class="archive-item__readmore">Read Article
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
							</span>
						</div>
					</div>
				</a>
			</article>
			<?php endwhile; ?>
		</div>

		<div class="archive-pagination">
			<?php
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Previous',
				'next_text' => 'Next <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>',
			) );
			?>
		</div>

		<?php else : ?>

		<div class="archive-empty">
			<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
			<h2>No Articles Found</h2>
			<p>There are no posts to display in this archive yet.</p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="archive-empty__btn">Back to Home</a>
		</div>

		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>
