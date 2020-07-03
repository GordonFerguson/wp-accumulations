<?php

/**
 * Admin screens
 */

add_action('admin_menu', 'bd324_foobot_add_admin_menu');
add_action('admin_init', 'bd324_foobot_settings_init');

function bd324_foobot_add_admin_menu()
{
	add_options_page(
		'Air Quality Data from Foobot',
		'Foobot API',
		'manage_options',
		'aq-data-foobot',
		'bd324_foobot_options_page'
	);
}

function bd324_settings_link($links)
{
	// Build and escape the URL.
	$url = esc_url(add_query_arg(
		'page',
		'aq-data-foobot',
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
add_filter('plugin_action_links_aq-data-foobot/aq-data-foobot.php', 'bd324_settings_link');

function bd324_foobot_settings_init()
{
	// Register
	register_setting(
		'bd324Foobot', 							// New options group
		'bd324_foobot_api_settings'			// Entry in options table
	);

	add_settings_section(
		'bd324-foobot-api-creds',						// Section ID
		__('Foobot API Credentials', 'aq-data-foobot'),	// Section header
		'bd324_foobot_settings_section_callback',	// Callback
		'bd324Foobot' 										// Add section to 
		// options group
	);

	// API Key
	add_settings_field(
		'bd324_foobot_api_key',						// ID
		__('API Key', 'aq-data-foobot'),					// Label
		'bd324_foobot_api_key_field_render',		// Function to display
		// inputs
		'bd324Foobot',									// Page to display on
		'bd324-foobot-api-creds'						// Section ID
	);

	// API username
	add_settings_field(
		'bd324_foobot_api_user',						// ID
		__('API User', 'aq-data-foobot'),				// Label
		'bd324_foobot_api_user_field_render',	// Function to
		// display inputs
		'bd324Foobot',									// Page to display on
		'bd324-foobot-api-creds'						// Section ID
	);
}

function bd324_foobot_api_user_field_render()
{
	$options = get_option('bd324_foobot_api_settings');
?>
	<input type='email' name='bd324_foobot_api_settings[bd324_foobot_api_user]' placeholder='<?php _e("Your API email", 'aq-data-foobot'); ?>' value='<?php echo esc_html($options['bd324_foobot_api_user']); ?>'>
<?php
}

function bd324_foobot_api_key_field_render()
{
	$options = get_option('bd324_foobot_api_settings');
?>
	<textarea rows="7" cols="50" name='bd324_foobot_api_settings[bd324_foobot_api_key]' placeholder='<?php _e("Your API key", 'aq-data-foobot'); ?>'><?php echo esc_textarea($options['bd324_foobot_api_key']); ?></textarea>
<?php
}
/**
 * Render the settings section content
 */
function bd324_foobot_settings_section_callback()
{
	_e('Add your Foobot API credentials below. An API key can be obtained at <a href="https://api.foobot.io/apidoc/index.html">api.foobot.io</a>.', 'aq-data-foobot');
}

/**
 * Render the options page form
 */

function bd324_foobot_options_page()
{
?>
	<div class="wrap">
		<form action='options.php' method='post'>

			<h1><?php _e("Air Quality Data from Foobot", 'aq-data-foobot'); ?></h1>
			<?php
			settings_fields('bd324Foobot');
			do_settings_sections('bd324Foobot');
			submit_button();
			?>

		</form>
	</div>
<?php
}
