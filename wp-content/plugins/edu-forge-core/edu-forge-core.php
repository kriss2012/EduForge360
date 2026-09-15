<?php
/**
 * Plugin Name:       EduForge360 Core
 * Plugin URI:        https://github.com/kriss2012/EduForge360
 * Description:       Industrial-level Student Development, Learning Management, Assessment, Skill Tracking and Placement Preparation Core Engine.
 * Version:           1.0.0
 * Author:            EduForge360 Development Team
 * Author URI:        https://eduforge360.edu
 * License:           GPL-2.0+
 * Text Domain:       eduforge360
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) && ! defined( 'ABSPATH' ) ) {
	die;
}

// Define Plugin Constants
define( 'EDUFORGE_VERSION', '1.0.0' );
define( 'EDUFORGE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'EDUFORGE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'EDUFORGE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function activate_eduforge_core() {
	require_once EDUFORGE_PLUGIN_DIR . 'includes/class-activator.php';
	EduForge_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_eduforge_core() {
	require_once EDUFORGE_PLUGIN_DIR . 'includes/class-deactivator.php';
	EduForge_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_eduforge_core' );
register_deactivation_hook( __FILE__, 'deactivate_eduforge_core' );

/**
 * Main Plugin Orchestrator Class
 */
final class EduForge_Core {

	/**
	 * Single instance of the class
	 * @var EduForge_Core
	 */
	protected static $_instance = null;

	/**
	 * Main Instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->set_locale();
		$this->init_hooks();
	}

	/**
	 * Load all core modules and managers
	 */
	private function load_dependencies() {
		// Core Database & Logging
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-logger.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-database.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-roles.php';

		// Post Types & Taxonomies
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-post-types.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-taxonomies.php';

		// Business Logic Managers
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-course-manager.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-lesson-manager.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-quiz-manager.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-assignment-manager.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-certificate-manager.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-skill-manager.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-career-manager.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-placement-manager.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-event-manager.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-woocommerce.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-analytics.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-notifications.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-cron.php';
		require_once EDUFORGE_PLUGIN_DIR . 'includes/class-shortcodes.php';

		// APIs & AJAX
		require_once EDUFORGE_PLUGIN_DIR . 'api/class-rest-api.php';
		require_once EDUFORGE_PLUGIN_DIR . 'api/class-ajax-handler.php';

		// Admin & Seeder
		if ( is_admin() ) {
			require_once EDUFORGE_PLUGIN_DIR . 'admin/class-admin-menu.php';
			require_once EDUFORGE_PLUGIN_DIR . 'admin/class-admin-settings.php';
			require_once EDUFORGE_PLUGIN_DIR . 'admin/class-admin-dashboard-widgets.php';
		}
		require_once EDUFORGE_PLUGIN_DIR . 'cli/class-demo-seeder.php';
	}

	/**
	 * Internationalization
	 */
	private function set_locale() {
		add_action( 'plugins_loaded', function() {
			load_plugin_textdomain(
				'eduforge360',
				false,
				dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);
		} );
	}

	/**
	 * Register actions and assets
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'register_core_systems' ), 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	/**
	 * Initialize core components
	 */
	public function register_core_systems() {
		EduForge_Roles::init();
		EduForge_Post_Types::init();
		EduForge_Taxonomies::init();
		EduForge_Shortcodes::init();
		EduForge_REST_API::init();
		EduForge_AJAX_Handler::init();
		EduForge_Cron::init();
		EduForge_WooCommerce::init();
	}

	/**
	 * Enqueue frontend scripts and localized objects
	 */
	public function enqueue_frontend_scripts() {
		wp_register_style( 'eduforge-core-styles', EDUFORGE_PLUGIN_URL . 'assets/css/core.css', array(), EDUFORGE_VERSION );
		wp_enqueue_style( 'eduforge-core-styles' );

		wp_register_script( 'eduforge-core-js', EDUFORGE_PLUGIN_URL . 'assets/js/core.js', array( 'jquery' ), EDUFORGE_VERSION, true );
		wp_localize_script( 'eduforge-core-js', 'eduforgeVars', array(
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
			'restUrl'   => esc_url_raw( rest_url( 'eduforge/v1/' ) ),
			'nonce'     => wp_create_nonce( 'eduforge_secure_nonce' ),
			'userId'    => get_current_user_id(),
			'isLoggedIn'=> is_user_logged_in(),
			'i18n'      => array(
				'loading'          => __( 'Processing...', 'eduforge360' ),
				'confirmEnroll'    => __( 'Are you sure you want to enroll in this course?', 'eduforge360' ),
				'completeLesson'   => __( 'Marking lesson as complete...', 'eduforge360' ),
				'submittingQuiz'   => __( 'Grading your quiz answers...', 'eduforge360' ),
				'applicationSent'  => __( 'Application successfully submitted!', 'eduforge360' ),
				'errorOccurred'    => __( 'Something went wrong. Please try again.', 'eduforge360' )
			)
		) );
		wp_enqueue_script( 'eduforge-core-js' );
	}

	/**
	 * Enqueue admin scripts & styles
	 */
	public function enqueue_admin_scripts( $hook ) {
		if ( strpos( $hook, 'eduforge' ) !== false || 'dashboard' === $hook ) {
			wp_enqueue_style( 'eduforge-admin-css', EDUFORGE_PLUGIN_URL . 'admin/css/admin.css', array(), EDUFORGE_VERSION );
			wp_enqueue_script( 'chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js', array(), '4.4.0', true );
			wp_enqueue_script( 'eduforge-admin-js', EDUFORGE_PLUGIN_URL . 'admin/js/admin.js', array( 'jquery', 'chartjs' ), EDUFORGE_VERSION, true );
			wp_localize_script( 'eduforge-admin-js', 'eduforgeAdminVars', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'eduforge_admin_nonce' ),
			) );
		}
	}
}

// Instantiate plugin singleton
function eduforge_core() {
	return EduForge_Core::instance();
}
eduforge_core();
