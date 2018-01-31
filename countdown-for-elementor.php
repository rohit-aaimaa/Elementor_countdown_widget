<?php

/**
 * Plugin Name: CountDown Widget
 * Description: CountDown Widget for Elementor
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'COUNTDOWN_ELEMENTOR_VERSION', '1.0.5' );

define( 'COUNTDOWN_ELEMENTOR__FILE__', __FILE__ );
define( 'COUNTDOWN_ELEMENTOR_PLUGIN_BASE', plugin_basename( COUNTDOWN_ELEMENTOR__FILE__ ) );
define( 'COUNTDOWN_ELEMENTOR_PATH', plugin_dir_path( COUNTDOWN_ELEMENTOR__FILE__ ) );
define( 'COUNTDOWN_ELEMENTOR_MODULES_PATH', COUNTDOWN_ELEMENTOR_PATH . 'modules/' );
define( 'COUNTDOWN_ELEMENTOR_URL', plugins_url( '/', COUNTDOWN_ELEMENTOR__FILE__ ) );
define( 'COUNTDOWN_ELEMENTOR_ASSETS_URL', COUNTDOWN_ELEMENTOR_URL . 'assets/' );
define( 'COUNTDOWN_ELEMENTOR_MODULES_URL', COUNTDOWN_ELEMENTOR_URL . 'modules/' );

// Load the plugin after Elementor (and other plugins) are loaded
add_action( 'plugins_loaded', function() {
	// Load localization file
	// load_plugin_textdomain( 'modal-for-elementor', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'countdown_for_elementor_fail_load' );
		return;
	}

	// Check version required
	$elementor_version_required = '1.8.5';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'countdown_for_elementor_fail_load_out_of_date' );
		return;
	}

	// Require the main plugin file
	require( COUNTDOWN_ELEMENTOR_PATH . 'plugin.php' );
} );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function countdown_for_elementor_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	if ( _is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

		$message = '<p>' . __( 'Elementor Starter is not working because you need to activate the Elementor plugin.', 'countdown-for-elementor' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'countdown-for-elementor' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

		$message = '<p>' . __( 'Modal For Elementor is not working because you need to install the Elemenor plugin', 'countdown-for-elementor' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'countdown-for-elementor' ) ) . '</p>';
	}

	echo '<div class="error"><p>' . $message . '</p></div>';
}

function countdown_for_elementor_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'Modal For Elementor is not working because you are using an old version of Elementor.', 'countdown-for-elementor' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'countdown-for-elementor' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

if ( ! function_exists( '_is_elementor_installed' ) ) {

	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}

function register_countdown_script() {
	wp_enqueue_script( 'countdown', plugin_dir_url( __FILE__ ) . 'js/jquery.countdown.min.js', array('jquery'), null, true );
}
add_action( 'wp_enqueue_scripts', 'register_countdown_script' );