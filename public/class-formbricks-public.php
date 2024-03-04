<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/formbricks/wordpress
 * @since      1.0.0
 *
 * @package    Formbricks
 * @subpackage Formbricks/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Formbricks
 * @subpackage Formbricks/public
 * @author     mcnaveen <me@mcnaveen.com>
 */
class Formbricks_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Formbricks_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Formbricks_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/formbricks-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		$globalToggle = get_option('formbricks_global_toggle');
		if (!empty($globalToggle) && $globalToggle == 'on') {
			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Formbricks_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Formbricks_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */

			$environment_id = get_option('formbricks_environment_id');
			$api_host = get_option('formbricks_api_host');

			if (!empty($environment_id) && !empty($api_host)) {
				wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/formbricks.umd.js', array('jquery'), $this->version, false);
				wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/index.js', array('jquery'), $this->version, false);
			}
		}
	}
}
