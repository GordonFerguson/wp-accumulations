<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
   die;
}

$options_name =     'baindesign_foobot_api_settings';

delete_option($options_name);

// drop a custom database tables
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bd_accumulations_mantra_data");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bd_accumulations_accumulation_data");

// Delete our transients
delete_transient( 'foobot-api-accumulation-updated');

/** 
 * TO DO
 * =====
 * To delete the accumulation transients, we need to get all the 
 * accumulation UUIDs and loop through them.
 */

// delete_transient( 'foobot-api-data-updated-' . $uuid);