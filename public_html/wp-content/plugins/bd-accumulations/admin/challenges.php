<?php

/**
 * A "challenge" is our name for a target number of accumulations
 */

function bd324_acc_fetch_challenge_total( $id )
{
   global $wpdb;
   $table   = $wpdb->prefix . 'bd_accumulations_accumulation_data';
//   $result = $wpdb->get_var( "SELECT sum(tally) FROM $table WHERE id = '$id' ");
   $result = $wpdb->get_results( "SELECT tally FROM $table WHERE id = '$id' ");
   return $result;
}

function bd324_acc_fetch_challenge_name( $id )
{
   global $wpdb;
   $table   = $wpdb->prefix . 'bd_accumulations_challenge_data';
   $result = $wpdb->get_results( "SELECT challengeName FROM $table WHERE id = '$id' ");
   return $result;
}

/**
 * Fetch all the challenges owned by a given user
 * @param $user_id
 * @return stdClass[]
 */
function bd324_acc_fetch_challenge_rows( $user_id )
{
   global $wpdb;
   $table   = $wpdb->prefix . 'bd_accumulations_challenge_data';
   $result = $wpdb->get_results( "SELECT * FROM $table WHERE idUser = '$user_id' ",OBJECT);
   return $result;
}

function mbd_challenge_update_sofar(){

  error_log("POST: ".print_r($_POST,true), 0);
  $update_parameters = array(
      'toDate'         =>  $_POST['accum'],
  );
  $where_parametes = array(
      'challenge_id'  => $_POST['challenge'], // id of selected option
  );

  global $wpdb;
  $table   = $wpdb->prefix . 'bd_accumulations_challenge_data';
  $wpdb->update($table, $update_parameters, $where_parametes);

  wp_redirect( $_SERVER["HTTP_REFERER"], 302, 'WordPress' );
  exit;
  status_header(200);
  die("Gracias, hemos recibido tu pedido.");
}

/**
 * Return the
 */