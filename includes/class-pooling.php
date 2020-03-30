<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://indie.systems/pooling-plugin
 * @since      1.0.0
 *
 * @package    PLG
 * @subpackage PLG/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    PLG
 * @subpackage PLG/includes
 * @author     Chris Dimas <info@indie.systems>
 */
class PLG
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      PLG_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $pooling    The string used to uniquely identify this plugin.
     */
    protected $pooling;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {

        $this->pooling = 'pooling';
        $this->version = '1.0.0';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - PLG_Loader. Orchestrates the hooks of the plugin.
     * - PLG_i18n. Defines internationalization functionality.
     * - PLG_Admin. Defines all hooks for the admin area.
     * - PLG_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/vendor/autoload.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-pooling-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-pooling-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-pooling-admin.php';

        /**
         * The class responsible for defining admin pages tabs.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pooling-wp-tabs.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-pooling-public.php';

        $this->loader = new PLG_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the PLG_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new PLG_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new PLG_Admin($this->get_pooling(), $this->get_version());
        $user         = new \PLGLib\User;
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu' );
        $this->loader->add_action('show_user_profile', $plugin_admin, 'extra_user_profile_fields');
        $this->loader->add_action('edit_user_profile', $plugin_admin, 'extra_user_profile_fields');
        $this->loader->add_action('user_new_form', $plugin_admin, 'extra_user_profile_fields');
        $this->loader->add_action('user_register', $user, 'update_user_profile_fields');
        $this->loader->add_action('personal_options_update', $user, 'update_user_profile_fields');
        $this->loader->add_action('edit_user_profile_update', $user, 'update_user_profile_fields');
        $this->loader->add_action('user_profile_update_errors', $user, 'user_update_errors');
        $this->loader->add_filter('manage_users_columns', $plugin_admin, 'add_user_column_verified_header');
        $this->loader->add_filter('manage_users_custom_column', $plugin_admin, 'add_user_column_verified_content', 10, 3);
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new PLG_Public($this->get_pooling(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('init', $plugin_public, 'add_new_member');
        $this->loader->add_action('init', $plugin_public, 'login_member');
        $this->loader->add_action('after_setup_theme', $plugin_public, 'remove_admin_bar');
        $user = new \PLGLib\User;
        $this->loader->add_action('pooling_after_update_meta', $user, 'verification_start');
        $this->loader->add_action('init', $user, 'verify_user_code');
        $this->loader->add_action('wp_authenticate_user', $user, 'check_user_verified');
        // $this->loader->add_action('registration_errors', $plugin_public, 'registration_errors');
        $this->loader->add_shortcode("pooling_main_map", $plugin_public, "main_map", $priority = 10, $accepted_args = 2);
        $this->loader->add_shortcode("pooling_login_form", $plugin_public, "login_form", $priority = 10, $accepted_args = 2);
        $this->loader->add_shortcode("pooling_registration_form", $plugin_public, "registration_form", $priority = 10, $accepted_args = 2);
        $this->loader->add_shortcode("pooling_account", $plugin_public, "user_profile_form", $priority = 10, $accepted_args = 2);
        $this->loader->add_shortcode("pooling_verify_code", $plugin_public, "verify_code", $priority = 10, $accepted_args = 2);
        $this->loader->add_shortcode("pooling_my_requests", $plugin_public, "my_requests", $priority = 10, $accepted_args = 2);
        // $this->loader->add_filter('script_loader_tag', $plugin_public, 'replace_script_att', 10, 2);
        $this->loader->add_action('wp_ajax_get_needs', $plugin_public, 'get_needs', 10, 2);
        $this->loader->add_action('wp_ajax_get_offers', $plugin_public, 'get_offers', 10, 2);
        $this->loader->add_action('wp_ajax_aid_request', $plugin_public, 'aid_request', 10, 2);
        $this->loader->add_action('wp_ajax_get_aid_request_id', $plugin_public, 'get_aid_request_id', 10, 2);
        $this->loader->add_action('wp_ajax_aid_request_withdraw', $plugin_public, 'aid_request_withdraw', 10, 2);
        $this->loader->add_action('wp_ajax_aid_request_accept', $plugin_public, 'aid_request_accept', 10, 2);

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_pooling()
    {
        return $this->pooling;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    PLG_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}
