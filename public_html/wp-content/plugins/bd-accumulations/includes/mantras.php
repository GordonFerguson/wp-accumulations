<?php

/**
 * Get the Mantras, i.e. a stdClass object with fields corresponding to the columns
 * @param $accumulation_name
 * @return stdClass[]
 */
function bd_accumulations_all_mantras($accumulation_name) {
  global $wpdb;
  $table   = $wpdb->prefix . 'bd_accumulations_mantra_data';
  $result = $wpdb->get_results( "SELECT * FROM $table ",OBJECT);
  return $result;
}