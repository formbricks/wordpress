<?php

/**
 *
 * @link              https://github.com/mcnaveen/formbricks-wp
 * @since             0.1.0
 * @package           Formbricks
 *
 * @wordpress-plugin
 * Plugin Name:       Formbricks
 * Plugin URI:        https://formbricks.com
 * Description:       Test desc
 * Version:           1.0.0
 * Author:            mcnaveen
 * Author Email:	  me@mcnaveen.com
 * Author URI:        https://github.com/mcnaveen/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       formbricks
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'FORMBRICKS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-formbricks-activator.php
 */
function activate_formbricks() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-formbricks-activator.php';
	Formbricks_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-formbricks-deactivator.php
 */
function deactivate_formbricks() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-formbricks-deactivator.php';
	Formbricks_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_formbricks' );
register_deactivation_hook( __FILE__, 'deactivate_formbricks' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-formbricks.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

function run_formbricks() {

	$plugin = new Formbricks();
	$plugin->run();

}
run_formbricks();


// Create the admin settings page
function formbricks_admin_settings_page() {

    add_menu_page(
        'Formbricks Settings',
        'Formbricks',
        'manage_options',
        'formbricks-settings',
        'formbricks_settings_page_content',
    );
}

function formbricks_settings_page_content() {
    // Check if the form has been submitted and settings have been saved
    if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
        // Display a success message
        echo '<div id="formbricks-settings-saved" class="updated notice is-dismissible"><p>Settings saved successfully!</p></div>';
    }
    ?>
    <div class="wrap">
        <h2>Formbricks Settings</h2>
        <p>You can find Environment ID and Host under <a href="https://formbricks.com/docs" target="_blank">Settings > Setup Checklist</a>.</p>
        <form method="post" action="options.php">
            <?php settings_fields('formbricks_settings_group'); ?>
            <?php do_settings_sections('formbricks-settings'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Environment ID:</th>
                    <td>
                        <input type="text" name="formbricks_environment_id" value="<?php echo esc_attr(get_option('formbricks_environment_id')); ?>" style="width: 50%;" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">API Host:</th>
                    <td>
                        <input type="text" name="formbricks_api_host" value="<?php echo esc_attr(get_option('formbricks_api_host')); ?>" style="width: 50%;" />
                    </td>
                </tr>
            </table>
            <input type="submit" class="button-primary" value="Save Changes">
        </form>
    </div>
    <script>
        // JavaScript to hide the settings saved alert after a few seconds
        jQuery(document).ready(function($) {
            setTimeout(function() {
                $('#formbricks-settings-saved').fadeOut('slow');
            }, 5000); // 5000 milliseconds = 5 seconds (adjust as needed)
        });
    </script>
    <?php
}


function formbricks_register_settings() {
    register_setting('formbricks_settings_group', 'formbricks_environment_id');
    register_setting('formbricks_settings_group', 'formbricks_api_host');
}

add_action('admin_menu', 'formbricks_admin_settings_page');
add_action('admin_init', 'formbricks_register_settings');

// Enqueue JavaScript on the frontend
function formbricks_enqueue_script() {
    if (!is_admin()) { // Load only on the frontend
        wp_enqueue_script(
            'formbricks-script',
            plugin_dir_url(__FILE__) . 'public/js/formbricks.js',
            array('jquery'), // Add any dependencies
            '1.0', // Your script version
            true // Load script in the footer
        );

        // Pass the environmentId and apiHost values to the script
        

        wp_localize_script('formbricks-script', 'myPluginSettings', array(
            'environmentId' => get_option('formbricks_environment_id'),
            'apiHost' => get_option('formbricks_api_host'),
        )); // Use 'myPluginSettings' here
    }
}

add_action('wp_enqueue_scripts', 'formbricks_enqueue_script');