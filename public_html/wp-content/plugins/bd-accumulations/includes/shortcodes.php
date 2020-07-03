<?php

/**
 * Shortcodes
 * ==========
 * 
 * Display the data
 */


// Show the data from a specific device
function bd_accumulations_shortcode_show_mantras( $atts )
{
  // e.g. [foobot-show-data device="BainBot"]

  // Debug
  // error_log("== SHORTCODE: Start [foobot-show-data] ==", 0);
  // error_log("FUNCTION: bd_accumulations_shortcode_show_mantras", 0);


  // Get attributes from shortcode
  $device_data = shortcode_atts( array(
      'device' => '',
  ), $atts );
  
  // Store atts in var
  $device_name = $device_data["device"];

  // Show the data
  $output = bd_accumulations_show_mantras( $device_name ); // mantras.php

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
