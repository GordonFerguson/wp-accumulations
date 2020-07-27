<?php

/**
 * Shortcodes
 * ==========
 * 
 * Display the data
 */


// Show the data from a specific accumulation
function bd_accumulations_shortcode_show_mantras($atts)
{

  // Get attributes from shortcode
  $accumulation_data = shortcode_atts(array(
    'accumulation' => '',
  ), $atts);

  // Store atts in var
  $accumulation_name = $accumulation_data["accumulation"];

  // Show the data
  $mantras = bd_accumulations_all_mantras($accumulation_name); // mantras.php

  // Output mantra data
  ob_start();
  if (empty($mantras)) {
    echo 'No Mantras are available';
  } else {
    foreach ($mantras as $mantra) {
//      bd_pretty_debug($mantra);
      echo '<div class="mantra-line" style="margin: 0;">'.$mantra->mantraName. ':  ' . $mantra->mantraDesc. '</div>';
    }
  }

  $content =  ob_get_contents();
  ob_clean();

  // Debug
  // error_log("== SHORTCODE: End [foobot-show-data] ==", 0);

  return $content;
}
add_shortcode('foobot_show_data', 'bd_accumulations_shortcode_show_mantras');

/**
 * Show a form to add or subtract accumulations
 */
function bd_accumulations_shortcode_add()
{

  ob_start();
    bd324_acc_add_accumulation_form();
    $content =  ob_get_contents();
  ob_clean();

  return $content;
}
add_shortcode('acc_form', 'bd_accumulations_shortcode_add');

/**
 * Show challenge data for the current user
 */
function bd_accumulations_shortcode_challenges($atts)
{
  $rows = bd324_acc_fetch_challenge_rows(get_current_user_id());
  ob_start();
    echo "<h5 style='color: darkred;'>TODO: student add/edit this list.</h5>";
    //bd_pretty_debug($name);
    if (empty($rows)) {
      echo 'No challenges found. Are you logged in as a student?<br>';
    } else {
      foreach ($rows as $challenge_row) {
        echo  $challenge_row->challengeName. ': goal is ' . $challenge_row->tally. '<br>';
      }
    }
    $content =  ob_get_contents();
  ob_clean();

  return $content;
}
add_shortcode('acc_challenge', 'bd_accumulations_shortcode_challenges');
