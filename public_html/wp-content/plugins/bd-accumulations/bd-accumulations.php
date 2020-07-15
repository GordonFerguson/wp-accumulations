<?php

/**
 * Plugin Name: WP Accumulations
 * Plugin URI: https://foobot.bain.design
 * Description: Accumulations
 * Author: Bain Design
 * Version: 0.0.0
 * Author URI: http://bain.design
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: bd-accumulations
 * Plugin Slug: bd-accumulations
 */

// Includes
	$path = plugin_dir_path(__FILE__);

	include( $path . 'lib/debug.php');
	include( $path . 'includes/database.php');

	// Admin
	// include( $path . 'admin/admin.php');
	// include( $path . 'admin/mantras.php');
	include( $path . 'admin/challenges.php');
	include( $path . 'admin/counts.php');

	// Frontend
	include( $path . 'includes/shortcodes.php');
	include( $path . 'includes/accumulations.php');
	// include( $path . 'includes/api.php');

	// Misc
	include( $path . 'includes/helpers.php');

/**
 * Plugin Init
 * ================
 */

function bd324_acc_plugin_init()
{

	function bdf_enqueue_styles() {
		wp_enqueue_style( 'bdf-style', plugins_url( 'assets/style.css', __FILE__ ), false );
		wp_enqueue_style( 'bdf-fonts', 'https://fonts.googleapis.com/css?family=Share+Tech+Mono&display=swap', false );
	}
	add_action('wp_enqueue_scripts','bdf_enqueue_styles');

}
add_action('plugins_loaded', 'bd324_acc_plugin_init');

/**
 * Activation Hooks
 * ================
 */

// Create database tables on plugin activation
register_activation_hook(__FILE__, 'bd_accumulations_create_mantra_table');
register_activation_hook(__FILE__, 'bd_accumulations_create_accumulation_table');
register_activation_hook(__FILE__, 'bd_accumulations_create_challenge_table');
