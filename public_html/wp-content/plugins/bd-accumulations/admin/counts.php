<?php 

function bd324_acc_add_accumulations()
{
   $current_user           = wp_get_current_user();
   $current_user_name      = $current_user->user_login;

   $mantra_name =          $_POST['mantra_name'];
   $accum =                $_POST['accum'];

   // Debug
   // bd_pretty_debug($_POST);

   $item = array(
      'mantra_name'        => $mantra_name,
      'accum'              => $accum,
      'user_name'          => $current_user_name
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
   // Get list of mantras
   global $wpdb;
   $table = $wpdb->prefix . 'bd_accumulations_mantra_data';

   $result = $wpdb->get_results( "SELECT * FROM $table ");

   $action = esc_url( admin_url("admin-post.php") );
   echo '<form method="POST" action="'. $action .'">';
   echo '<select name="mantra_name"  id="progress"  class="select-select2">';
   echo '<option value="">Select Mantra</option>';

   foreach ($result as $row) {
      $id   = $row->id;
      $mantra = $row->mantraName;
      echo '<option value = '.$mantra.'>'.$mantra.'</option>';
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