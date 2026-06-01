<?php

function enqueue_custom_styles()
{
	wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap', array(), null);
	wp_enqueue_style('custom-style', get_stylesheet_directory_uri() . '/assets/css/custom.css', array(), '1.0.0');
	wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_styles');

// Add Hero Banner on Homepage
function add_hero_banner_section()
{
	if (is_front_page() || is_home()) {
?>
		<section class="hero-banner">
			<div class="hero-bg-animation">
				<div class="floating-shapes">
					<div class="shape shape-1"></div>
					<div class="shape shape-2"></div>
					<div class="shape shape-3"></div>
					<div class="shape shape-4"></div>
					<div class="shape shape-5"></div>
				</div>
				<div class="code-rain"></div>
				<div class="grid-overlay"></div>
			</div>

			<div class="hero-content">
				<div class="hero-badge">
					<span class="badge-icon">🚀</span>
					<span class="badge-text">Tech • Code • AI</span>
				</div>

				<h1 class="hero-title">
					<span class="title-line">Exploring the Future of</span>
					<span class="title-highlight">
						<span class="typing-text"></span>
						<span class="cursor">|</span>
					</span>
				</h1>

				<p class="hero-description">
					Dive into the world of cutting-edge technology, programming tutorials,
					and AI innovations. Your journey to becoming a tech expert starts here.
				</p>

				<div class="hero-stats">
					<div class="stat-item">
						<span class="stat-icon">📚</span>
						<span class="stat-number" data-count="100">0</span>
						<span class="stat-label">Articles</span>
					</div>
					<div class="stat-item">
						<span class="stat-icon">💻</span>
						<span class="stat-number" data-count="50">0</span>
						<span class="stat-label">Tutorials</span>
					</div>
					<div class="stat-item">
						<span class="stat-icon">🤖</span>
						<span class="stat-number" data-count="25">0</span>
						<span class="stat-label">AI Projects</span>
					</div>
				</div>

				<div class="hero-cta">
					<a href="#content" class="btn-primary">
						<span>Explore Articles</span>
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M5 12h14M12 5l7 7-7 7" />
						</svg>
					</a>
					<a href="#categories" class="btn-secondary">
						<span>Browse Topics</span>
					</a>
				</div>

				<div class="hero-tags">
					<span class="tag">Python</span>
					<span class="tag">JavaScript</span>
					<span class="tag">Machine Learning</span>
					<span class="tag">Web Dev</span>
					<span class="tag">AI/ML</span>
					<span class="tag">DevOps</span>
				</div>
			</div>

			<div class="hero-visual">
				<div class="code-window">
					<div class="window-header">
						<div class="window-dots">
							<span class="dot red"></span>
							<span class="dot yellow"></span>
							<span class="dot green"></span>
						</div>
						<span class="window-title">main.py</span>
					</div>
					<div class="window-content">
						<pre><code><span class="code-keyword">def</span> <span class="code-function">learn_tech</span>():
    <span class="code-variable">skills</span> = [<span class="code-string">"AI"</span>, <span class="code-string">"Web"</span>, <span class="code-string">"Cloud"</span>]
    <span class="code-keyword">for</span> skill <span class="code-keyword">in</span> skills:
        <span class="code-function">master</span>(skill)
    <span class="code-keyword">return</span> <span class="code-string">"Success! 🎉"</span></code></pre>
					</div>
				</div>

				<div class="floating-icons">
					<div class="icon-float icon-1">⚛️</div>
					<div class="icon-float icon-2">🐍</div>
					<div class="icon-float icon-3">🤖</div>
					<div class="icon-float icon-4">☁️</div>
					<div class="icon-float icon-5">🔧</div>
				</div>
			</div>

			<div class="scroll-indicator">
				<span>Scroll to explore</span>
				<div class="scroll-arrow">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M12 5v14M5 12l7 7 7-7" />
					</svg>
				</div>
			</div>
		</section>
<?php
	}
}
add_action('generate_after_header', 'add_hero_banner_section', 5);
