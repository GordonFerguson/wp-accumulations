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

   add_submenu_page(
      'bd-accumulations',
      __('Mantras', '_bd324-accmulations'),
      __('Mantras', '_bd324-accmulations'),
      'manage_options',
      'admin.php?page=bd-accum-mantras',
      'bd324_accum_mantras_page',
   );

   add_submenu_page(
      'bd-accumulations',
      __('Goals', '_bd324-accmulations'),
      __('Goals', '_bd324-accmulations'),
      'manage_options',
      'admin.php?page=bd-accum-goals',
      // 'bd324_accum_goals_page',
   );
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
}

function bd324_acc_api_user_field_render()
{
   $options = get_option('bd324_acc_api_settings');
?>
   <input type='email' name='bd324_acc_api_settings[bd324_acc_api_user]' placeholder='<?php _e("Your API email", '_bd324-accmulations'); ?>' value='<?php echo esc_html($options['bd324_acc_api_user']); ?>'>
<?php
}

function bd324_acc_api_key_field_render()
{
   $options = get_option('bd324_acc_api_settings');
?>
   <textarea rows="7" cols="50" name='bd324_acc_api_settings[bd324_acc_api_key]' placeholder='<?php _e("Your API key", '_bd324-accmulations'); ?>'><?php echo esc_textarea($options['bd324_acc_api_key']); ?></textarea>
<?php
}
/**
 * Render the settings section content
 */
function bd324_acc_settings_section_callback()
{
   _e('Add your Foobot API credentials below. An API key can be obtained at <a href="https://api.foobot.io/apidoc/index.html">api.foobot.io</a>.', '_bd324-accmulations');
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

// Form to add accumulation via Admin screen
function bd324_acc_add_accumulation_form_page_handler()
{
   echo '<form method="POST" action="?page=add_data">
   <label>Mantra Name: </label><input type="text" name="mantra_name" /><br />
   <label>Accumulations: </label><input type="number" name="accum" /><br />
  <input type="submit" value="submit" />
  </form>';

   $default = array(
      'mantra_name' => '',
      'accum' => '',
   );
   $item = shortcode_atts($default, $_REQUEST);
   // Debug
   bd_pretty_debug($_REQUEST, $name = '_REQUEST');

   bd_accumulations_add_db_accumulations($item);
}
