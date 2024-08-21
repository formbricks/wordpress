<?php

/*
 * Plugin Name:       Formbricks
 * Plugin URI:        https://github.com/formbricks/wordpress
 * Description:       Official WordPress Plugin for Formbricks | An Open Source Survey Platform
 * Version:           1.0.1
 * Author:            Formbricks
 * Author Email:      hola@formbricks.com
 * Author URI:        https://formbricks.com
 * License:           GPL v2
 * License URI:       ./LICENSE.txt
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('FORMBRICKS_VERSION', '1.0.1');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-formbricks-activator.php
 */
function formbricks_activate_plugin()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-formbricks-activator.php';
    Formbricks_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-formbricks-deactivator.php
 */
function formbricks_deactivate_plugin()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-formbricks-deactivator.php';
    Formbricks_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'formbricks_activate_plugin');
register_deactivation_hook(__FILE__, 'formbricks_deactivate_plugin');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-formbricks.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

 function formbricks_run_plugin()
 {

    $plugin = new Formbricks();
    $plugin->run();
}
formbricks_run_plugin();


function formbricks_admin_settings_page()
{

    add_menu_page(
        'Formbricks Settings',
        'Formbricks',
        'manage_options',
        'formbricks-settings',
        'formbricks_settings_page_content',
        plugin_dir_url(__FILE__) . 'public/formbricks.svg',
    );
}

function formbricks_settings_page_content()
{
    $nonce = wp_create_nonce('formbricks_settings_nonce');

    if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
        if (check_admin_referer('formbricks_settings_nonce', 'formbricks_settings_nonce_field')) {
            echo '<div id="formbricks-settings-saved" class="updated notice is-dismissible"><p>Settings saved successfully!</p></div>';
        } else {
            echo '<div id="formbricks-settings-error" class="error notice is-dismissible"><p>Error saving settings: Nonce verification failed. Please try again.</p></div>';
        }
    }
?>
    <div class="wrap">
        <h2>Formbricks Settings</h2>

        <!-- Setup Checklist -->
        <div class="container">
            <h3>Setup Checklist</h3>
            <p>To run targeted surveys on your website, you need to complete the following steps:</p>

            <ul>
                <li>1. Free Formbricks Account (No Credit Card Needed) <a href="https://app.formbricks.com/auth/signup" target="_blank">Signup here</a></li>
                <li>2. The <b>environment ID</b> of your account. You find it in your Formbricks account settings under "Setup Checklist"</li>
                <li>3. The <b>API Host</b> When you use the Formbricks Cloud it is always "https://app.formbricks.com". Only when you self-host Formbricks, it would be different</li>
                <li>4. Here is a <a href="https://formbricks.com/docs/getting-started/framework-guides#prerequisites" target="_blank">detailed guide</a> on how to get started</li>
            </ul>
        </div>

        <!-- Configuration -->
        <div class="container">
            <form method="post" action="options.php">
                <?php wp_nonce_field('formbricks_settings_nonce', 'formbricks_settings_nonce_field'); ?>
                <?php settings_fields('formbricks_settings_group'); ?>
                <?php do_settings_sections('formbricks-settings'); ?>
                <h3>Configuration</h3>

                <div>
                    <div>
                        <b>Environment ID</b>
                        <p>You can find your environment ID in your Formbricks account under "Setup Checklist". It looks like: clmyq4k....</p>
                        <input type="text" name="formbricks_environment_id" id="formbricks_environment_id" value="<?php echo esc_attr(get_option('formbricks_environment_id')); ?>" />
                    </div>
                    <div style="margin-top: 20px;">
                        <b>API Host</b>
                        <p>When you use the Formbricks Cloud it is always "https://app.formbricks.com". Only when you self-host Formbricks, it would be different</p>
                        <input type="text" name="formbricks_api_host" id="formbricks_api_host" value="<?php echo esc_attr(get_option('formbricks_api_host')); ?>" />
                    </div>
                    <div style="margin-top: 20px;">
                        <button type="button" id="formbricks-test-ping" class="test-button">Check Connection</button>
                        <button type="submit" id="formbricks-save-changes" class="save-button" disabled>Save Changes</button>
                        <p id="formbricks-ping-result"></p>
                    </div>
                </div>
            </form>
        </div>

        <!-- Enable Formbricks -->
        <div class="container">
            <form method="post" action="options.php">
                <?php wp_nonce_field('formbricks_toggle_nonce', 'formbricks_toggle_nonce_field'); ?>
                <?php settings_fields('formbricks_toggle_group'); ?>
                <?php do_settings_sections('formbricks-toggle'); ?>
                <h3>Enable Formbricks</h3>
                <!-- Show Formbricks is enabled or not -->
                <p>
                    <b>Formbricks is currently: </b>
                    <?php
                    $globalToggle = get_option('formbricks_global_toggle');
                    if ($globalToggle == 'on') {
                        echo '<span style="color: green;">Enabled ✅</span>';
                    } else {
                        echo '<span style="color: red;">Disabled ❌</span>';
                    }
                    ?>
                </p>
                <label class="toggle-switch">
                    <input type="checkbox" name="formbricks_global_toggle" <?php checked(get_option('formbricks_global_toggle'), 'on'); ?> />
                    <span class="slider"></span>
                </label>
                <div style="margin-top: 20px;">
                    <input type="submit" class="save-button" value="Save Changes" />
                </div>
                <div style="margin-top: 20px;">
                <b>What now?</b>
                    <p>
                        Create a survey on Formbricks to display on your website. <a href="https://formbricks.com/docs/integrations/wordpress" target="_blank">Here is a step by step guide</a>
                    </p>
                    <p>
                        Help needed? <a href="https://formbricks.com/discord" target="_blank">Join our Discord Community</a>
                    </p>
                </div>
            </form>
        </div>
    </div>


    <script>
        jQuery(document).ready(function($) {
            setTimeout(function() {
                $('#formbricks-settings-saved').fadeOut('slow');
            }, 5000);
        });
    </script>

    <script>
        jQuery(document).ready(function($) {
            // Function to handle ping response and update UI
            function handlePingResponse(response) {
                var enableSaveChanges = response && response.data && response.data.product;

                $('#formbricks-ping-result').html(enableSaveChanges ?
                    '<span style="color: green;">Test Request Success! Click the Save Changes Button</span>' :
                    '<span style="color: red;">Error: Invalid response format!</span>'
                );

                $('#formbricks-save-changes').prop('disabled', !enableSaveChanges);
            }

            // Function to handle ping error and update UI
            function handlePingError() {
                $('#formbricks-ping-result').html('<span style="color: red;">Test Request Failed! Make sure both field values are correct!</span>');
                $('#formbricks-save-changes').prop('disabled', true);
            }

            // Event binding for the "Test Ping" button
            $('#formbricks-test-ping').on('click', function() {
                var environmentId = $('#formbricks_environment_id').val();
                var apiHost = $('#formbricks_api_host').val();
                apiHost = apiHost.replace(/\/$/, '');

                if (environmentId && apiHost) {
                    // Perform AJAX request
                    $.ajax({
                        url: apiHost + '/api/v1/client/' + environmentId + '/in-app/sync',
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            handlePingResponse(response);
                        },
                        error: function(xhr, status, error) {
                            handlePingError();
                        }
                    });
                } else {
                    handlePingError();
                }
            });

            // Event binding for the "Save Changes" button
            $('#formbricks-save-changes').on('click', function() {
                // Remove trailing slash from the apiHost
                var apiHost = $('#formbricks_api_host').val();
                $('#formbricks_api_host').val(apiHost.replace(/\/$/, ''));
            });

        });
    </script>

    <script>
        jQuery(document).ready(function($) {
            setTimeout(function() {
                $('#formbricks-settings-saved').fadeOut('slow');
            }, 5000);
        });
    </script>
<?php
}

function formbricks_register_toggle_settings()
{
    register_setting('formbricks_toggle_group', 'formbricks_global_toggle', function($option) {
        if (check_admin_referer('formbricks_toggle_nonce', 'formbricks_toggle_nonce_field')) {
            return $option;
        } else {
            add_settings_error('formbricks_global_toggle', 'formbricks_toggle_nonce_error', 'Error saving settings: Nonce verification failed. Please try again.');
            return get_option('formbricks_global_toggle');
        }
    });
}


function formbricks_register_settings()
{
    register_setting('formbricks_settings_group', 'formbricks_environment_id', 'sanitize_text_field');
    register_setting('formbricks_settings_group', 'formbricks_api_host', 'esc_url_raw');
}

add_action('admin_menu', 'formbricks_admin_settings_page');
add_action('admin_init', 'formbricks_register_settings');
add_action('admin_init', 'formbricks_register_toggle_settings');


// Enqueue JavaScript on the frontend
function formbricks_enqueue_script() {
    if (!is_admin()) {
        // Check if the global toggle is on
        $globalToggle = get_option('formbricks_global_toggle');

        if ($globalToggle == 'on') {
            // Get options
            $environmentId = get_option('formbricks_environment_id');
            $apiHost       = get_option('formbricks_api_host');

            if (!empty($environmentId) && !empty($apiHost)) {
                wp_enqueue_script(
                    'formbricks',
                    $apiHost . '/api/packages/website',
                    array('jquery'),
                    '1.0.1',
                    true
                );

                // Enqueue index.js after formbricks
                wp_enqueue_script(
                    'formbricks-init',
                    plugin_dir_url(__FILE__) . 'public/js/index.js',
                    array('jquery', 'formbricks'), // Add 'formbricks' as a dependency
                    '1.0.1',
                    true
                );

                wp_add_inline_script('formbricks',
                    'const formbricksPluginSettings = ' . wp_json_encode(array(
                        'environmentId' => $environmentId,
                        'apiHost'       => $apiHost,
                    )),
                    'before'
                );
            }
        } else {
            // Formbricks is disabled
        }
    }
}

add_action('wp_enqueue_scripts', 'formbricks_enqueue_script');