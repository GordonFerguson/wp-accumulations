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
  // e.g. [foobot-show-data accumulation="BainBot"]

  // Debug
  // error_log("== SHORTCODE: Start [foobot-show-data] ==", 0);
  // error_log("FUNCTION: bd_accumulations_shortcode_show_mantras", 0);


  // Get attributes from shortcode
  $accumulation_data = shortcode_atts(array(
    'accumulation' => '',
  ), $atts);

  // Store atts in var
  $accumulation_name = $accumulation_data["accumulation"];

  // Show the data
  $output = bd_accumulations_show_mantras($accumulation_name); // mantras.php

  // Output mantra data
  ob_start();
  echo $output;

  $content =  ob_get_contents();
  ob_clean();

  // Debug
  // error_log("== SHORTCODE: End [foobot-show-data] ==", 0);

  return $content;
}
add_shortcode('foobot-show-data', 'bd_accumulations_shortcode_show_mantras');

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
