<?php
/**
 * Contributor functions and definitions
 *
 * @package Contributor
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

class Contributor_Theme {

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		$this->load_files();
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	public function setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Contributor, use a find and replace
		 * to change 'contributor' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'contributor', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		//add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => __( 'Primary Menu', 'contributor' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
		) );

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside', 'image', 'video', 'quote', 'link',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'contributor_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );
	}

	/**
	 * Register widget area.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
	 */
	public function widgets_init() {
		register_sidebar( array(
			'name'          => __( 'Sidebar', 'contributor' ),
			'id'            => 'sidebar-1',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		) );
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'contributor-style', get_stylesheet_uri() );

		wp_enqueue_script( 'contributor-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

		wp_enqueue_script( 'contributor-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}


	private function load_files() {
		/**
		 * Main class that builds elements based on the user of WordPress.org
		 */
		require get_template_directory() . '/inc/profile-builder.php';
		$GLOBALS['contributor_theme_profile'] = new Contributor_Profile_Builder(get_theme_mod('contributor_usernamdde'));

		/**
		 * Custom template tags for this theme.
		 */
		require get_template_directory() . '/inc/template-tags.php';

		/**
		 * Custom functions that act independently of the theme templates.
		 */
		require get_template_directory() . '/inc/extras.php';

		/**
		 * Customizer additions.
		 */
		require get_template_directory() . '/inc/customizer.php';

		if ( is_admin() ) {
			/**
			 * Add theme settings page
			 */
			require get_template_directory() . '/inc/theme-settings.php';
			$GLOBALS['contributor_theme_settings'] = new Contributor_Theme_Settings();
		}
	}

}

$GLOBALS['contributor_theme'] = new Contributor_Theme();
