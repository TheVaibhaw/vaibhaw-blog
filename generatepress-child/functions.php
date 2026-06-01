<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
function generatepress_child_enqueue_scripts() {
	wp_enqueue_style( 'generatepress-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'generatepress-child-custom-style', get_stylesheet_directory_uri() . '/assets/css/style.css', array( 'generatepress-style' ), wp_get_theme()->get( 'Version' ) );
	wp_enqueue_script( 'generatepress-child-custom-script', get_stylesheet_directory_uri() . '/assets/js/script.js', array(), wp_get_theme()->get( 'Version' ), true );
}
add_action( 'wp_enqueue_scripts', 'generatepress_child_enqueue_scripts', 20 );

function generatepress_child_remove_footer_credit( $credits ) {
	return '&copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' );
}
add_filter( 'generate_copyright', 'generatepress_child_remove_footer_credit' );

function generatepress_child_create_blog_page() {
	$page = get_page_by_path( 'blog' );
	if ( ! $page ) {
		$page_id = wp_insert_post( array(
			'post_title'   => 'Blog',
			'post_name'    => 'blog',
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => '',
		) );
		if ( $page_id && ! is_wp_error( $page_id ) ) {
			update_post_meta( $page_id, '_wp_page_template', 'page-templates/blog.php' );
		}
	}
}
add_action( 'after_switch_theme', 'generatepress_child_create_blog_page' );

function generatepress_child_ensure_blog_page() {
	$page = get_page_by_path( 'blog' );
	if ( ! $page ) {
		generatepress_child_create_blog_page();
	}
}
add_action( 'init', 'generatepress_child_ensure_blog_page' );
/**
 * 🛠️ SEO & Google Search Console Optimization
 */
function generatepress_child_seo_metadata() {
    ?>
    <!-- Google Search Console Verification -->
    <meta name="google-site-verification" content="maW-hJdQ9AJySI8xUreMqnx8hi79D-N356K_k1qL4MU" />
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Vaibhaw Kumar (Vaibhaw Parashar) - Official Blog. Deep dives into web development, React, Next.js, WordPress, and tech insights. Best web developer in Mohali and Saran, Bihar." />
    <meta name="keywords" content="Vaibhaw Kumar, Vaibhaw Kumar Parashar, Vaibhaw Parashar, Tech Prastish Vaibhaw Kumar, Vaibhaw Mohali, Vaibhaw Kumar Mohali, Vaibhaw Kumar Bihar, Vaibhaw Kumar Chapra, Web Developer, Software Engineer, WordPress Expert" />
    <link rel="canonical" href="<?php echo esc_url( home_url( add_query_arg( null, null ) ) ); ?>" />
    
    <!-- Open Graph / Social Media -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Vaibhaw Kumar | Tech & Code Insights" />
    <meta property="og:description" content="Official Blog of Vaibhaw Kumar Parashar. Expertise in Web Development, Software Engineering, and Digital Solutions." />
    <meta property="og:url" content="<?php echo esc_url( home_url( '/' ) ); ?>" />
    <meta property="og:site_name" content="Vaibhaw Kumar" />
    
    <!-- Schema.org for AI & Search Engines -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "WebSite",
          "name": "Vaibhaw Kumar Blog",
          "url": "<?php echo esc_url( home_url( '/' ) ); ?>",
          "description": "Tech and programming blog by Vaibhaw Kumar Parashar"
        },
        {
          "@type": "Person",
          "name": "Vaibhaw Kumar Parashar",
          "url": "https://vaibhawkumarparashar.in",
          "sameAs": [
            "https://linkedin.com/in/itsvaibhaw/",
            "https://github.com/thevaibhaw"
          ],
          "jobTitle": "Web Developer",
          "worksFor": {
            "@type": "Organization",
            "name": "Tech Prastish"
          }
        }
      ]
    }
    </script>
    <?php
}
add_action( 'wp_head', 'generatepress_child_seo_metadata', 1 );
