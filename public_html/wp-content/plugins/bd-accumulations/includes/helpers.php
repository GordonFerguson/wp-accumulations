<?php

/** 
 * Get accumulation UUID
 * ===============
 * For use in shortcode where the user
 * gives the name of the accumulation they want 
 * to get the data from.
 */

function bd_get_foobot_accumulation_uuid( $accumulation_name )
{
   $accumulations = bd_accumulations_fetch_db_accumulations();

   // Get array columns
   $col = array_column( $accumulations, 'name' );
   
   // Get the array key
   $name = $accumulation_name . ' ';   // API returns accumulation names
                                 // with a trailing space.

   $key = array_search( $name, $col );
   if( $key===false ){
      return 'error_accumulation_not_found';
      // error_log('accumulation "' . $accumulation_name . '" not found', 0);
   } else {
      $uuid = $accumulations[$key]["uuid"];   
      return $uuid;

      // debug
      // error_log("FUNCTION: bd_get_foobot_accumulation_uuid (" .$accumulation_name. ")", 0);
   }


}

function bd_get_mantra_id_from_name( $mantra_name )
{
   $accumulations = bd_accumulations_fetch_db_accumulations();

   // Get array columns
   $col = array_column( $accumulations, 'name' );
   
   // Get the array key
   $name = $accumulation_name . ' ';   // API returns accumulation names
                                 // with a trailing space.

   $key = array_search( $name, $col );
   if( $key===false ){
      return 'error_accumulation_not_found';
      // error_log('accumulation "' . $accumulation_name . '" not found', 0);
   } else {
      $uuid = $accumulations[$key]["uuid"];   
      return $id;

      // debug
      // error_log("FUNCTION: bd_get_foobot_accumulation_uuid (" .$accumulation_name. ")", 0);
   }


}