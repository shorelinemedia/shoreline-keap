<?php
/*
Plugin Name: 			Shoreline Keap
Plugin URI: 			https://shoreline.media
Description: 			Send form leads to Keap
Version: 				0.2
Author: 				Shoreline Media Marketing
Author URI: 			https://shoreline.media
License: 				GPLv2 or later
License URI: 			http://www.gnu.org/licenses/gpl-2.0.html
Github Updater URI: 	https://github.com/shorelinemedia/shoreline-keap
*/

global $infusionsoft;

require_once plugin_dir_path( __FILE__ ) . 'includes/infusionsoft.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/infusionsoft-examples.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/infusionsoft-gravityforms.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/infusionsoft-settings.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

// Load main Infusionsoft API
$settings = (array) get_option( 'infusionsoft_settings' );
if ( isset( $settings['subdomain'] ) && isset( $settings['api_key'] ) && isset( $settings['gf_integration'] ) ) {
	$infusionsoft = new Infusionsoft( $settings['subdomain'], $settings['api_key'] );

	// Make sure Infusionsoft connected
	if ( is_wp_error( $infusionsoft->error ) ) {
		$error = $infusionsoft->error->get_error_message();
		add_action( 'admin_notices', function() use ( $error ) {
			echo "<div class=\"error\"><p><strong>Keap Error:</strong> " . $error . "</p></div>";
		});
	}

}

class Infusionsoft_WP {
	/**
	 * Calls all actions and hooks used by the plugin
	 */
	public function __construct() {
		$settings = (array) get_option( 'infusionsoft_settings' );

		// Load Gravity Forms integration if enabled
		if ( isset( $settings['gf_integration'] ) && $settings['gf_integration'] && ! is_plugin_active( 'infusionsoft/infusionsoft.php' ) ) {
			$infusionsoft_gravityforms = new Infusionsoft_GravityForms;
		}
	}
}

// Start the plugin
$infusionsoft_wp = new Infusionsoft_WP;