<?php
/**
 * Main plugin class.
 *
 * @package MI_Elementor_Addons
 */

namespace MI_Elementor_Addons;

defined( 'ABSPATH' ) || exit;

final class Plugin {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function init() {
		load_plugin_textdomain(
			'mi-elementor-addons',
			false,
			dirname( plugin_basename( MI_EA_FILE ) ) . '/languages'
		);

		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'elementor_missing_notice' ) );
			return;
		}

		require_once MI_EA_PATH . 'includes/widgets/class-product-card.php';
		require_once MI_EA_PATH . 'includes/widgets/class-product-grid.php';

		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
	}

	public function register_category( $elements_manager ) {
		$elements_manager->add_category(
			'mi-elementor-addons',
			array(
				'title' => esc_html__( 'MI Addons', 'mi-elementor-addons' ),
				'icon'  => 'eicon-library-open',
			)
		);
	}

	public function register_widgets( $widgets_manager ) {
		$widgets_manager->register( new Widgets\Product_Card() );
		$widgets_manager->register( new Widgets\Product_Grid() );
	}

	public function register_assets() {
		wp_register_style(
			'mi-elementor-addons',
			MI_EA_URL . 'assets/css/frontend.css',
			array(),
			MI_EA_VERSION
		);

		wp_register_style(
			'mi-elementor-addons-product-grid',
			MI_EA_URL . 'assets/css/product-grid.css',
			array(),
			MI_EA_VERSION
		);
	}

	public function elementor_missing_notice() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		printf(
			'<div class="notice notice-warning"><p>%s</p></div>',
			esc_html__( 'MI Elementor Addons requires Elementor to be installed and active.', 'mi-elementor-addons' )
		);
	}
}
