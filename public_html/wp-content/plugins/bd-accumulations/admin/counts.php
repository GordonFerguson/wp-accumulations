<?php 

  function bd324_acc_add_accumulations() {
//    error_log("FUNCTION: bd324_acc_add_accumulations ".$_POST['challenge'], 0);

  $update_parameters = array(
     'challenge_id'  => $_POST['challenge'], // id of selected option
     'tally'         => $_POST['accum'],
  );

  // Add form data to table
  bd_accumulations_add_db_accumulations( $update_parameters );

   wp_redirect( $_SERVER["HTTP_REFERER"], 302, 'WordPress' );
   exit;
   status_header(200);
   die("Gracias, hemos recibido tu pedido.");

}

// Form to add accumulation via Admin screen
function bd324_acc_add_accumulation_form()
{
   // Get list of challenges
   // TODO Get active challenges
   global $wpdb;
   $table = $wpdb->prefix . 'bd_accumulations_challenge_data';
   $challenges = $wpdb->get_results( "SELECT * FROM $table ");
   $action = esc_url( admin_url("admin-post.php") );

   echo '<form method="POST" action="'. $action .'">';
   echo "<input type='hidden' name='action' value='add_to_tally'>";
   echo '<select name="challenge"  id="challenge"  class="select-select2">';

   echo '<option value="">Select Challenge</option>'; // title
   foreach ($challenges as $challenge) {
      $id   = $challenge->id;
      $name = $challenge->challengeName;
      $to_date = $challenge->toDate;
      echo "<option value = '$id' data-hours='$to_date'>$name</option>";
   }
   echo '</select></br>';

   // Accumulations counts
   echo '<label>Enter your accumulation for the selected challenge: </label>';
   echo '<input type="number" name="accum" id="accumulator"/><br />';
   echo '<input type="hidden" name="action" value="add_to_tally">';
   // Submit
   echo '<input type="submit" value="submit" />';

   echo '</form>';

}

add_action ( 'admin_post_add_to_tally', 'mbd_challenge_update_sofar' );