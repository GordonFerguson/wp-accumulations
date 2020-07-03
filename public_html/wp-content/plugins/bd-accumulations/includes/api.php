<?php

/**
 * This function should not be called directly!!!
 * ==============================================
 * 
 * Using our API key, we question the API to get the UUID of the 
 * Foobot accumulation. With this info, we can go on to call mantra data. 
 * 
 * This retrieved data should then be added to the database.
 * 
 * We must never call this function directly! Instead, we must
 * retrieve the data we have stored in our custom database table (see 
 * "database.php"). This function is only used to update the 
 * table. 
 * 
 */
function bd_accumulations_call_api_accumulations()
{

   // Vars
   $key = bd_accumulations_get_api_key();
   $user = bd_accumulations_get_api_user();
   $url = 'https://api.foobot.io/v2/owner/' . $user . '/accumulation/';
   $args = array('headers' => array('X-API-KEY-TOKEN' => $key));

   // Request
   $request = wp_remote_get($url, $args);

   if (is_wp_error($request)) {
      return false; // Bail early
   }

   $body = wp_remote_retrieve_body($request);

   $api_data = json_decode( $body, true);

   // debug
   error_log("FUNCTION: bd_accumulations_call_api_accumulations", 0);

   return $api_data;
}

/**
 * This function should not be called directly!!!
 * ==============================================
 * 
 * Get the API data
 * ================
 * 
 * Now that we have the accumulation UUID, we can call for 
 * the data from the accumulation.
 * 
 * We must never call this function directly! Instead, we must
 * retrieve the data we have stored in our custom database table (see 
 * "database.php"). This function is only used to update the 
 * table.
 * 
 */

function bd_accumulations_call_api_mantras( $uuid )
{
   $key = bd_accumulations_get_api_key();

   $url = 'https://api.foobot.io/v2/accumulation/' . $uuid . '/datapoint/0/last/0/?' . $key;
   $args = array(
      'headers' => array(
         'X-API-KEY-TOKEN' => $key
      )
   );

   $request = wp_remote_get($url, $args);

   if (is_wp_error($request)) {
      return false; // Bail early
   }

   $body = wp_remote_retrieve_body($request);

   $api_data = json_decode( $body, true); // Output array

   // debug
   // error_log("FUNCTION: bd_accumulations_call_api_mantras", 0);
   return $api_data;
}

/**
 * 
 * These functions use transients to avoid hitting the API
 * limit. 
 */

// Update accumulation data
function bd_accumulations_call_api_trans_accumulations()
{
   global $wpdb;

   // debug
   error_log("FUNCTION: bd_accumulations_call_api_trans_accumulations", 0);

   // If an API call has been made within the last 24 hours, 
   // return.
   if (1 == get_transient('foobot-api-accumulation-updated')) {
      // Debug
      // error_log("NOTICE: No Foobot accumulation API call made at this time.", 0);
      return;
   }

   // Get the accumulation data
   $accumulation_data = bd_accumulations_call_api_accumulations();

   // Transient is set for 24 hours
   set_transient('foobot-api-accumulation-updated', 1, (60 * 60 * 24));

   // Debug
   // error_log("NOTICE: Foobot mantra data has been updated! Next update > 24 hours.", 0);

   return $accumulation_data;
}

// Update mantra data
function bd_accumulations_call_api_trans_mantras( $uuid )
{
   global $wpdb;

   // debug
   // error_log("FUNCTION: bd_accumulations_call_api_trans_mantras", 0);

   // If an API call has been made within the last 5 mins, 
   // return.
   if (1 == get_transient('foobot-api-data-updated-' . $uuid )) {
      // Debug
      // error_log("No Foobot mantra API call made at this time.", 0);

      return;
   }

   // Get the accumulation data
   $data = bd_accumulations_call_api_mantras( $uuid );
   if (is_wp_error($data)) {
      // error_log("Error: No data from Foobot mantra API ", 0);
      return false; // Bail early
   }

   // Transient is set for 10 mins
   set_transient('foobot-api-data-updated-' . $uuid, 1, (60 * 10));

   // Debug
   // error_log("Foobot mantra data has been updated! Next update > 10 mins.", 0);

   return $data;
}