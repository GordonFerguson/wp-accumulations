<?php

/**
 * Set database versions
 */

// accumulation
global $bd_accumulations_accumulation_db_version;
$bd_accumulations_accumulation_db_version = '1.2';

// mantras
global $bd_accumulations_mantra_db_version;
$bd_accumulations_mantra_db_version = '1.4';

/** Get Options
 * ============
 * 
 * Retrieve values stored in WordPress database
 * options table. 
 */

// Get API key from the database
function bd_accumulations_get_api_key()
{
   $options = get_option('bd324_acc_api_settings');
   return $options['bd324_acc_api_key'];
}

// Get API username from the database
function bd_accumulations_get_api_user()
{
   $options = get_option('bd324_acc_api_settings');
   return $options['bd324_acc_api_user'];
}

/**
 * ======================
 * Create database tables
 * ======================
 */

/**
 * Create the custom tables needed for this plugin
 */

// Create table to store mantra data
function bd_accumulations_create_mantra_table()
{
   global $wpdb;
   global $bd_accumulations_mantra_db_version;

   $table_name = $wpdb->prefix . 'bd_accumulations_mantra_data';

   $charset_collate = $wpdb->get_charset_collate();

   $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
      timestamp int NOT NULL,
      mantraName tinytext NOT NULL,
		mantraDesc tinytext NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql);

   add_option('bd_accumulations_mantra_db_version', $bd_accumulations_mantra_db_version);
}

// Create table to store accumulation data
function bd_accumulations_create_accumulation_table()
{
   global $wpdb;
   global $bd_accumulations_accumulation_db_version;

   $table_name = $wpdb->prefix . 'bd_accumulations_accumulation_data';

   $charset_collate = $wpdb->get_charset_collate();

   $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		timestamp int NOT NULL,
		mantraName tinytext NOT NULL,
		userName tinytext NOT NULL,
		tally float NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql);

   add_option('bd_accumulations_accumulation_db_version', $bd_accumulations_accumulation_db_version);
}

/**
 * ============================
 * Fetch data from the database
 * ============================
 */

// Fetch mantra data
function bd_accumulations_fetch_latest_mantra_data()
{

   // To DO
   // Pass the mantra you want to this function

   // Vars
   global $wpdb;
   $table_name = $wpdb->prefix . 'bd_accumulations_mantra_data';

   // $data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table_name}` ORDER BY `id` DESC LIMIT 1", $mantra) );
   $data = $wpdb->get_row("SELECT * FROM `{$table_name}` ORDER BY `id` DESC LIMIT 1", ARRAY_A);

   return $data;
}

// Query the database for mantra 
// data from a specific accumulation
function bd_accumulations_fetch_db_mantras($uuid)
{

   // Debug
   error_log("FUNCTION: bd_accumulations_fetch_db_mantras(" . $uuid . ")", 0);

   global $wpdb;
   $wpdb->show_errors();

   // Vars
   $table_name = $wpdb->prefix . 'bd_accumulations_mantra_data';

   // Update the accumulation table if required
   bd_accumulations_update_mantra_data($uuid);

   // Now we query the db.
   $data = $wpdb->get_results("SELECT * FROM `{$table_name}` WHERE `uuid`='$uuid' ORDER BY `id` DESC LIMIT 1", ARRAY_A);

   return $data;

   // Show error if any
   $wpdb->print_error();
}

// Fetch accumulation data
function bd_accumulations_fetch_db_accumulations()
{

   // debug
   error_log("FUNCTION: bd_accumulations_fetch_db_accumulations()", 0);

   global $wpdb;
   $wpdb->show_errors();

   // Vars
   $table_name = $wpdb->prefix . 'bd_accumulations_accumulation_data';

   // Update the accumulation table if required
   bd_accumulations_update_accumulation_data();

   // Get all the results
   // TO DO: Only return results from the last 24 hours?

   // Order the results

   // Get the most recent result and return rows that
   // match the same timestamp

   //$data = $wpdb->get_row( "SELECT * FROM `{$table_name}` WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 1 DAY)", ARRAY_A );
   $data = array();
   $data = $wpdb->get_row("SELECT * FROM `{$table_name}` ORDER BY `id` DESC LIMIT 1", ARRAY_A);

   $timestamp = $data["timestamp"];

   //$latest = array();
   $latest = $wpdb->get_results("SELECT * FROM `{$table_name}` WHERE `timestamp`= $timestamp ORDER BY `id` DESC", ARRAY_A);
   if (count($latest) > 0) {
      return $latest;   // returns an array with the latest accumulations
   } else {
      return;
   }

   //echo '<h3>Latest</h3>';
   //echo '<pre><code>';
   //var_dump( $latest );
   //echo '</code></pre>';

   // Show error if any
   // $wpdb->print_error();
}

/**
 * =========================
 * Add data to the database
 * =========================
 */

// Add accumulation data to database
function bd_accumulations_add_db_accumulations($accumulation_data)
{
   global $wpdb;   
   
   $table_name = $wpdb->prefix . 'bd_accumulations_accumulation_data';
   $time = current_time('timestamp');
   
   $wpdb->insert(
      $table_name,
      array(
         'timestamp'    => $time,
         'mantraName'   => $accumulation_data['mantra_name'],
         'userName'     => $accumulation_data['user_name'],
         'tally'        => $accumulation_data['accum'],
      ),
      array(
         '%d',
         '%s',
         '%s',
         '%f'
         )
      );
      
      /* Debug */
      // bd_pretty_debug($wpdb->insert);
      // $wpdb->show_errors();
      // error_log("FUNCTION: bd_accumulations_add_db_accumulations", 0);
}

// Add mantra data to database
function bd_accumulations_add_db_mantras($data)
{

   global $wpdb;

   // DEBUG
   // $wpdb->show_errors(); // Turn on errors display

   $table_name = $wpdb->prefix . 'bd_accumulations_mantra_data';

   // Vars
   $time                   = $data['start'];
   $uuid                   = $data['uuid'];

   // Units
   $unitPm                 = $data['units'][1];
   $unitTmp                = $data['units'][2];
   $unitHum                = $data['units'][3];
   $unitCo2                = $data['units'][4];
   $unitVoc                = $data['units'][5];
   $unitAllpollu           = $data['units'][6];

   // Datapoints
   $datapointPm            = $data['datapoints'][0][1];
   $datapointTmp           = $data['datapoints'][0][2];
   $datapointHum           = $data['datapoints'][0][3];
   $datapointCo2           = $data['datapoints'][0][4];
   $datapointVoc           = $data['datapoints'][0][5];
   $datapointAllpollu      = $data['datapoints'][0][6];

   // Insert data into db table
   $wpdb->insert(
      $table_name,
      array(
         'timestamp'          => $time,
         'uuid'               => $uuid,
         'unitPm'             => $unitPm,
         'datapointPm'        => $datapointPm,
         'unitTmp'            => $unitTmp,
         'datapointTmp'       => $datapointTmp,
         'unitHum'            => $unitHum,
         'datapointHum'       => $datapointHum,
         'unitCo2'            => $unitCo2,
         'datapointCo2'       => $datapointCo2,
         'unitVoc'            => $unitVoc,
         'datapointVoc'       => $datapointVoc,
         'unitAllpollu'       => $unitAllpollu,
         'datapointAllpollu'  => $datapointAllpollu,
      ),
      array(
         '%d', // 'timestamp'
         '%s', // 'uuid'
         '%s', // 'unitPm'
         '%f', // 'datapointPm'
         '%s', // 'unitTmp'
         '%f', // 'datapointTmp'
         '%s', // 'unitHum'
         '%f', // 'datapointHum'
         '%s', // 'unitCo2'
         '%f', // 'datapointCo2'
         '%s', // 'unitVoc'
         '%f', // 'datapointVoc'
         '%s', // 'unitAllpollu'
         '%f', // 'datapointAllpollu'
      )
   );

   // error_log("EVENT | Database: New mantra data added", 0);

   // DEBUG
   // $wpdb->print_error(); // Show error if any

}

function bd_accumulations_update_accumulation_data()
{
   /**
    * Request an API call
    * (checks if transient set, if
    * not, makes API call)
    */
   $data = bd_accumulations_call_api_trans_accumulations();

   if ($data) {
      /**
       * If the request returns data
       * (i.e. transient not set)
       * update the database
       */
      bd_accumulations_add_db_accumulations($data);
   }
}

function bd_accumulations_update_mantra_data($uuid)
{
   /**
    * Request an API call
    * (checks if transient set, if
    * not, makes API call)
    */
   $data = bd_accumulations_call_api_trans_mantras($uuid);

   if ($data) {
      /**
       * If the request returns data
       * (i.e. transient not set)
       * update the database
       */
      bd_accumulations_add_db_mantras($data);
   }
}
