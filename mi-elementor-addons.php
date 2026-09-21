<?php
/**
 * Plugin Name: MI Elementor Addons
 * Description: Modular Elementor widgets by MI Plugins.
 * Version: 0.1.0
 * Author: MI Plugins
 * Text Domain: mi-elementor-addons
 * Requires at least: 6.4
 * Requires PHP: 7.4
 * Requires Plugins: elementor
 */

defined( 'ABSPATH' ) || exit;

define( 'MI_EA_VERSION', '0.1.0' );
define( 'MI_EA_FILE', __FILE__ );
define( 'MI_EA_PATH', plugin_dir_path( __FILE__ ) );
define( 'MI_EA_URL', plugin_dir_url( __FILE__ ) );

require_once MI_EA_PATH . 'includes/class-plugin.php';

MI_Elementor_Addons\Plugin::instance();
