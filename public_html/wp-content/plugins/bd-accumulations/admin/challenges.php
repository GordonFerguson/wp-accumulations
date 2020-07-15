<?php

/**
 * A "challenge" is our name for a target number of accumulations
 */

function bd324_acc_fetch_challenge_total( $id )
{
   global $wpdb;
   $table   = $wpdb->prefix . 'bd_accumulations_accumulation_data';
   $result = $wpdb->get_var( "SELECT sum(tally) FROM $table WHERE challengeID = '$id' ");
   return $result;
}

function bd324_acc_fetch_challenge_name( $id )
{
   global $wpdb;
   $table   = $wpdb->prefix . 'bd_accumulations_challenge_data';
   $result = $wpdb->get_results( "SELECT challengeName FROM $table WHERE id = '$id' ");
   return $result;
}