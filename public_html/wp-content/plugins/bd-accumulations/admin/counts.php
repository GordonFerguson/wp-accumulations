<?php 

function bd324_acc_add_accumulations()
{
   $current_user           = wp_get_current_user();
   $current_user_id      = $current_user->id;

   $challenge_id =       $_POST['challenge_id'];
   $accum =                $_POST['accum'];

   // Debug
   // bd_pretty_debug($_POST);

   $item = array(
      'challenge_id'        => $challenge_id,
      'accum'              => $accum,
      'user_id'          => $current_user_id
   );

   // Add form data to table
   bd_accumulations_add_db_accumulations( $item );

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

   $result = $wpdb->get_results( "SELECT * FROM $table ");

   $action = esc_url( admin_url("admin-post.php") );
   echo '<form method="POST" action="'. $action .'">';
   echo "<input type='hidden' name='action' value='add_to_tally'>";
   echo '<select name="challenge"  id="challenge"  class="select-select2">';

   echo '<option value="">Select Challenge</option>'; // title
   foreach ($result as $accumulation_row) {
      $id   = $accumulation_row->id;
      $name = $accumulation_row->challengeName;
      echo '<option value = '.$id.'>'.$name.'</option>';
   }
   echo '</select></br>';

   // Accumulations counts
   echo '<label>Accumulations: </label><input type="number" name="accum" /><br />';
   echo '<input type="hidden" name="action" value="add_to_tally">';
   // Submit
   echo '<input type="submit" value="submit" />';

   echo '</form>';

}



add_action ( 'admin_post_add_to_tally', 'bd324_acc_add_accumulations' );