<?php
defined('ABSPATH') || exit;
get_header();
?>

<div class="error-page">
	<div class="error-container">
		<div class="error-visual" data-animate="fade-up">
			<span class="error-code">404</span>
			<div class="error-glow" aria-hidden="true"></div>
		</div>
		<div class="error-content" data-animate="fade-up" data-delay="200">
			<h1 class="error-title">Page Not Found</h1>
			<p class="error-desc">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
			<div class="error-actions">
				<a href="<?php echo esc_url(home_url('/')); ?>" class="error-btn error-btn--primary">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
						<polyline points="9 22 9 12 15 12 15 22" />
					</svg>
					<span>Back to Home</span>
				</a>
				<a href="<?php echo esc_url(home_url('/blog/')); ?>" class="error-btn error-btn--outline">
					<span>Browse Articles</span>
				</a>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>