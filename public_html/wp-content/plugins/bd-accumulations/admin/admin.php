<?php

/**
 * Admin screens
 */

add_action('admin_menu', 'bd324_acc_add_admin_menu');
add_action('admin_init', 'bd324_acc_settings_init');

function bd324_acc_add_admin_menu()
{
   add_menu_page(
      __('Accumulations', '_bd324-accmulations'),
      __('Accumulations', '_bd324-accmulations'),
      'manage_options',
      'bd-accumulations',
      'bd324_acc_options_page',
      'dashicons-plus' // TODO: Replace with SVG icon
   );
   /*
   add_submenu_page(
      'bd-accumulations',
      __('Accumulations', '_bd324-accmulations'),
      __('Accumulations', '_bd324-accmulations'),
      'manage_options',
      'bd-accumulations',       // Menu Slug
   );

   add_submenu_page(
      'bd-accumulations',
      __('Challenges', '_bd324-accmulations'),
      __('Challenges', '_bd324-accmulations'),
      'manage_options',
      'admin.php?page=bd-accum-goals',       // Menu Slug
      'bd324_accum_goals_page',              // Function
   );


   add_submenu_page(
      'bd-accumulations',
      __('Mantras', '_bd324-accmulations'),
      __('Mantras', '_bd324-accmulations'),
      'manage_options',
      'admin.php?page=bd-accum-mantras',
      'bd324_accum_mantras_page',
   );

*/
}

function bd324_settings_link($links)
{
   // Build and escape the URL.
   $url = esc_url(add_query_arg(
      'page',
      '_bd324-accmulations',
      get_admin_url() . 'options-general.php'
   ));
   // Create the link.
   $settings_link = "<a href='$url'>" . __('Settings') . '</a>';
   // Adds the link to the end of the array.
   array_push(
      $links,
      $settings_link
   );
   return $links;
}
add_filter('plugin_action_links__bd324-accmulations/_bd324-accmulations.php', 'bd324_settings_link');

function bd324_acc_settings_init()
{
   // Register
   register_setting(
      'bd324Foobot',                      // New options group
      'bd324_acc_api_settings'         // Entry in options table
   );

   add_settings_section(
      'bd324-foobot-api-creds',                  // Section ID
      __('Foobot API Credentials', '_bd324-accmulations'),   // Section header
      'bd324_acc_settings_section_callback',   // Callback
      'bd324Foobot'                               // Add section to 
      // options group
   );

   // API Key
   add_settings_field(
      'bd324_acc_api_key',                  // ID
      __('API Key', '_bd324-accmulations'),               // Label
      'bd324_acc_api_key_field_render',      // Function to display
      // inputs
      'bd324Foobot',                           // Page to display on
      'bd324-foobot-api-creds'                  // Section ID
   );

   // API username
   add_settings_field(
      'bd324_acc_api_user',                  // ID
      __('API User', '_bd324-accmulations'),            // Label
      'bd324_acc_api_user_field_render',   // Function to
      // display inputs
      'bd324Foobot',                           // Page to display on
      'bd324-foobot-api-creds'                  // Section ID
   );

   // Add accumulations
   add_settings_field(
      'bd324_acc_add_accumulations', // ID
      __('Add Accumulations', '_bd324-accmulations'), // Label
      'bd324_acc_add_accumulation_form_page_handler', // Callback
      'bd324Foobot', // Page to display on
      'bd324-foobot-api-creds' // Section ID
   );

   // Add mantra
   add_settings_field(
      'bd324_acc_add_mantra', // ID
      __('Add Mantra', '_bd324-accmulations'), // Label
      'bd324_acc_add_mantra_form_page_handler', // Callback
      'bd324Foobot', // Page to display on
      'bd324-foobot-api-creds' // Section ID
   );
}



/**
 * Render the settings section content
 */
function bd324_acc_settings_section_callback()
{
   _e('Add (or subtract) accumulations here.', '_bd324-accmulations');
}

/**
 * Render the options page form
 */

function bd324_acc_options_page()
{
?>
   <div class="wrap">
      <form action='options.php' method='post'>

         <h1><?php _e("Accumulations", '_bd324-accmulations'); ?></h1>
         <?php
         settings_fields('bd324Foobot');
         do_settings_sections('bd324Foobot');
         submit_button();
         ?>

      </form>
   </div>
<?php
}

/**
 * Render the goals page form
 */

function bd324_acc_goals_page()
{
?>
   <div class="wrap">
      <h1><?php _e("Accumulations", '_bd324-accmulations'); ?></h1>
      <form method="POST" action="?page=add_data">
         <label>Mantra Name: </label><input type="text" name="mantra_name" /><br />
         <label>Accumulations: </label><input type="number" name="accum" /><br />
         <input type="submit" value="submit" />
      </form>
   </div>
<?php
}


