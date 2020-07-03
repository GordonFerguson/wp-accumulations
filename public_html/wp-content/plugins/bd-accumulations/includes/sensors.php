<?php

/**
 * =============================
 * Get readings from the mantras
 * =============================
 */

// Show the data from a specific accumulation
function bd_accumulations_show_mantras( $accumulation_name )
{
  // debug
  // error_log("FUNCTION: bd_accumulations_show_mantras (" .$accumulation_name. ")", 0);

  // Get the target accumulation UUID
  $uuid = bd_get_foobot_accumulation_uuid( $accumulation_name );
  if($uuid==='error_accumulation_not_found'){
    $content = '<div class="foobot-data foobot-data__error">Sorry, the accumulation "'.$accumulation_name.'" has not been found. Please check the accumulation name and try again.</div>';
    return $content;
  }
  
  // Fetch the mantra data from the database
  $mantra_data = bd_accumulations_fetch_db_mantras( $uuid );

  if (count($mantra_data)> 0){

    // Remove one level from the array
    $data = $mantra_data[0];

    // Data age
    $now = time();
    $data_age = $now - esc_html( $data['timestamp'] );

    // Pretty up the data
    $Tmp_data = round( $data['datapointTmp'], 1 );
    $Pm_data = round( $data['datapointPm'], 1 );
    $Co2_data = round( $data['datapointCo2'], 1 );
    $Voc_data = round( $data['datapointVoc'], 1 );
    $Hum_data = round( $data['datapointHum'], 1 );
    $All_data = round( $data['datapointAllpollu'], 1 );


    // Output mantra data
    $content = '<div class="foobot-data"><ul class="mantras">';
    $content.= '<li class="mantra mantra--tmp"><span class="mantra__label">' . __('Temperature', 'aq-data-foobot') . '</span><span class="mantra__data">' . $Tmp_data . '</span><span class="mantra__unit">' . $data['unitTmp'] . '</span></li>' ;
    $content.= '<li class="mantra mantra--pm"><span class="mantra__label">' . __('PM', 'aq-data-foobot') . '</span><span class="mantra__data">' . $Pm_data . '</span><span class="mantra__unit">µg/m3</span></li>' ;
    $content.= '<li class="mantra mantra--co2"><span class="mantra__label">' . __('Co2', 'aq-data-foobot') . '</span><span class="mantra__data">' . $Co2_data . '</span><span class="mantra__unit">' . $data['unitCo2'] . '</span></li>' ;
    $content.= '<li class="mantra mantra--voc"><span class="mantra__label">' . __('VOC', 'aq-data-foobot') . '</span><span class="mantra__data">' . $Voc_data . '</span><span class="mantra__unit">' . $data['unitVoc'] . '</span></li>' ;
    $content.= '<li class="mantra mantra--hum"><span class="mantra__label">' . __('Humidity', 'aq-data-foobot') . '</span><span class="mantra__data">' . $Hum_data . '</span><span class="mantra__unit">' . $data['unitHum'] . '</span></li>' ;
    $content.= '<li class="mantra mantra--all"><span class="mantra__label">' . __('All', 'aq-data-foobot') . '</span><span class="mantra__data">' . $All_data . '</span><span class="mantra__unit">' . $data['unitAllpollu'] . '</span></li>' ;
    $content.= '</ul>';
    $content.= sprintf( __('<div class="mantra__data-age">Data from %s updated %d<span class="s">s</span> ago</div>', 'aq-data-foobot'), $accumulation_name, $data_age );
    $content.= '</div>';
  } else {
    // Error message
    $content = '<div class="foobot-data foobot-data__error">Sorry, something went wrong. Please try again later</div>';
  }

  return $content;



}

/** 
 * Get current temperature and return it as array
 */
function bd_get_temp_now($uuid)
{
   /**
    * First, we need to check our transient and update the data in the 
    * custom table if necessary. 
    */
    bd_accumulations_update_mantra_data($uuid);

   /**
    * Having done that, we can proceed with questioning the database.
    */

   $data = bd_accumulations_fetch_latest_mantra_data();

   return $data;
}
