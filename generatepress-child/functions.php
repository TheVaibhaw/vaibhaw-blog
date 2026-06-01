<?php
/**
 * GeneratePress Child Theme Functions
 *
 * @package    GeneratePress_Child
 * @author     Your Name
 * @copyright  2026 Your Name
 * @license    GPL-2.0-or-later
 * @version    1.0.0
 *
 * @wordpress-theme
 */

// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define theme constants.
 */
define( 'GENERATEPRESS_CHILD_VERSION', '1.0.0' );
define( 'GENERATEPRESS_CHILD_DIR', get_stylesheet_directory() );
define( 'GENERATEPRESS_CHILD_URI', get_stylesheet_directory_uri() );

/**
 * Class GeneratePress_Child_Theme
 *
 * Main theme class that handles all theme functionality.
 *
 * @since 1.0.0
 */
final class GeneratePress_Child_Theme {

	/**
	 * Single instance of the class.
	 *
	 * @since 1.0.0
	 * @var GeneratePress_Child_Theme|null
	 */
	private static $instance = null;

	/**
	 * Theme version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $version = GENERATEPRESS_CHILD_VERSION;

	/**
	 * Get the single instance of the class.
	 *
	 * @since  1.0.0
	 * @return GeneratePress_Child_Theme
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function init_hooks() {
		// Theme setup.
		add_action( 'after_setup_theme', array( $this, 'theme_setup' ), 15 );

		// Enqueue scripts and styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 20 );

		// Admin scripts and styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Editor styles.
		add_action( 'after_setup_theme', array( $this, 'add_editor_styles' ), 20 );

		// Register widget areas.
		add_action( 'widgets_init', array( $this, 'register_widget_areas' ), 20 );

		// Custom body classes.
		add_filter( 'body_class', array( $this, 'custom_body_classes' ) );

		// Defer non-critical scripts.
		add_filter( 'script_loader_tag', array( $this, 'add_defer_attribute' ), 10, 2 );
	}

	/**
	 * Theme setup.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function theme_setup() {
		// Load child theme text domain.
		load_child_theme_textdomain(
			'generatepress-child',
			GENERATEPRESS_CHILD_DIR . '/languages'
		);

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );

		// Add support for wide alignment.
		add_theme_support( 'align-wide' );

		// Add custom image sizes.
		add_image_size( 'gp-child-featured', 1200, 630, true );
		add_image_size( 'gp-child-thumbnail', 400, 300, true );
	}

	/**
	 * Enqueue parent and child theme styles.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_styles() {
		// Get parent theme version for cache busting.
		$parent_theme   = wp_get_theme( 'generatepress' );
		$parent_version = $parent_theme->exists() ? $parent_theme->get( 'Version' ) : '1.0.0';

		// Enqueue parent theme stylesheet.
		wp_enqueue_style(
			'generatepress-style',
			get_template_directory_uri() . '/style.css',
			array(),
			$parent_version
		);

		// Enqueue child theme stylesheet.
		wp_enqueue_style(
			'generatepress-child-style',
			GENERATEPRESS_CHILD_URI . '/style.css',
			array( 'generatepress-style' ),
			$this->version
		);

		// Enqueue custom styles.
		wp_enqueue_style(
			'generatepress-child-custom',
			GENERATEPRESS_CHILD_URI . '/assets/css/custom.css',
			array( 'generatepress-child-style' ),
			$this->version
		);

		// Add inline CSS for critical above-the-fold styles.
		$critical_css = $this->get_critical_css();
		if ( ! empty( $critical_css ) ) {
			wp_add_inline_style( 'generatepress-child-custom', $critical_css );
		}
	}

	/**
	 * Enqueue theme scripts.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_scripts() {
		// Enqueue custom JavaScript.
		wp_enqueue_script(
			'generatepress-child-custom',
			GENERATEPRESS_CHILD_URI . '/assets/js/custom.js',
			array(),
			$this->version,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);

		// Localize script with data.
		wp_localize_script(
			'generatepress-child-custom',
			'gpChildData',
			array(
				'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'gp_child_nonce' ),
				'siteUrl'   => home_url(),
				'themeUrl'  => GENERATEPRESS_CHILD_URI,
				'isRTL'     => is_rtl(),
				'isMobile'  => wp_is_mobile(),
				'i18n'      => array(
					'loading'    => esc_html__( 'Loading...', 'generatepress-child' ),
					'error'      => esc_html__( 'An error occurred.', 'generatepress-child' ),
					'success'    => esc_html__( 'Success!', 'generatepress-child' ),
					'scrollTop'  => esc_html__( 'Scroll to top', 'generatepress-child' ),
				),
			)
		);

		// Conditional scripts.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @since  1.0.0
	 * @param  string $hook The current admin page hook.
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook ) {
		// Only load on specific admin pages if needed.
		$allowed_hooks = array( 'post.php', 'post-new.php', 'widgets.php' );

		if ( ! in_array( $hook, $allowed_hooks, true ) ) {
			return;
		}

		wp_enqueue_style(
			'generatepress-child-admin',
			GENERATEPRESS_CHILD_URI . '/assets/css/admin.css',
			array(),
			$this->version
		);
	}

	/**
	 * Add editor styles.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function add_editor_styles() {
		add_editor_style( 'assets/css/editor-style.css' );
	}

	/**
	 * Register additional widget areas.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_widget_areas() {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Child Theme Widget Area', 'generatepress-child' ),
				'id'            => 'gp-child-widget-area',
				'description'   => esc_html__( 'Custom widget area added by child theme.', 'generatepress-child' ),
				'before_widget' => '<section id="%1$s" class="widget gp-child-widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}

	/**
	 * Add custom body classes.
	 *
	 * @since  1.0.0
	 * @param  array $classes Existing body classes.
	 * @return array Modified body classes.
	 */
	public function custom_body_classes( $classes ) {
		// Add child theme class.
		$classes[] = 'gp-child-theme';

		// Add class for specific templates.
		if ( is_page_template() ) {
			$template   = get_page_template_slug();
			$classes[] = 'template-' . sanitize_html_class( basename( $template, '.php' ) );
		}

		// Add class for logged-in users.
		if ( is_user_logged_in() ) {
			$classes[] = 'gp-child-logged-in';
		}

		// Add class for touch devices (will be toggled via JS).
		$classes[] = 'no-touch';

		return $classes;
	}

	/**
	 * Add defer attribute to specific scripts.
	 *
	 * @since  1.0.0
	 * @param  string $tag    The script tag.
	 * @param  string $handle The script handle.
	 * @return string Modified script tag.
	 */
	public function add_defer_attribute( $tag, $handle ) {
		$defer_scripts = array(
			'generatepress-child-custom',
		);

		if ( in_array( $handle, $defer_scripts, true ) ) {
			// Check if defer is already present.
			if ( strpos( $tag, 'defer' ) === false ) {
				$tag = str_replace( ' src', ' defer src', $tag );
			}
		}

		return $tag;
	}

	/**
	 * Get critical CSS for above-the-fold content.
	 *
	 * @since  1.0.0
	 * @return string Critical CSS.
	 */
	private function get_critical_css() {
		$css = '
			/* Critical above-the-fold styles */
			.site-header { display: block; }
			.main-navigation { display: flex; }
		';

		/**
		 * Filter critical CSS.
		 *
		 * @since 1.0.0
		 * @param string $css The critical CSS.
		 */
		return apply_filters( 'generatepress_child_critical_css', trim( $css ) );
	}
}

/**
 * Initialize the theme.
 *
 * @since  1.0.0
 * @return GeneratePress_Child_Theme
 */
function generatepress_child() {
	return GeneratePress_Child_Theme::get_instance();
}

// Initialize.
generatepress_child();

/**
 * Helper function to get theme asset URL.
 *
 * @since  1.0.0
 * @param  string $path Asset path relative to theme directory.
 * @return string Full URL to the asset.
 */
function gp_child_asset_url( $path ) {
	return GENERATEPRESS_CHILD_URI . '/' . ltrim( $path, '/' );
}

/**
 * Helper function to get theme asset path.
 *
 * @since  1.0.0
 * @param  string $path Asset path relative to theme directory.
 * @return string Full path to the asset.
 */
function gp_child_asset_path( $path ) {
	return GENERATEPRESS_CHILD_DIR . '/' . ltrim( $path, '/' );
}

/**
 * Helper function to include template part with data.
 *
 * @since 1.0.0
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialized template (optional).
 * @param array  $args Additional arguments passed to the template (optional).
 * @return void
 */
function gp_child_get_template_part( $slug, $name = null, $args = array() ) {
	/**
	 * Fires before a template part is loaded.
	 *
	 * @since 1.0.0
	 * @param string $slug The slug name for the template.
	 * @param string $name The name of the specialized template.
	 * @param array  $args Additional arguments passed to the template.
	 */
	do_action( 'gp_child_before_template_part', $slug, $name, $args );

	get_template_part( $slug, $name, $args );

	/**
	 * Fires after a template part is loaded.
	 *
	 * @since 1.0.0
	 * @param string $slug The slug name for the template.
	 * @param string $name The name of the specialized template.
	 * @param array  $args Additional arguments passed to the template.
	 */
	do_action( 'gp_child_after_template_part', $slug, $name, $args );
}
