<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<section class="hero-banner" id="hero">
    <div class="hero-bg-animation">
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
            <div class="shape shape-5"></div>
        </div>
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
            <a href="#main-content" class="btn-primary">
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

<div id="main-content" class="site-main-content">
    <div <?php generate_do_attr('content'); ?>>
        <main <?php generate_do_attr('main'); ?>>
            <?php
            do_action('generate_before_main_content');

            if (generate_has_default_loop()) {
                if (have_posts()) :
                    do_action('generate_before_loop', 'index');

                    while (have_posts()) :
                        the_post();
                        generate_do_template_part('index');
                    endwhile;

                    do_action('generate_after_loop', 'index');
                else :
                    generate_do_template_part('none');
                endif;
            }

            do_action('generate_after_main_content');
            ?>
        </main>
    </div>
</div>

<?php
get_footer();
