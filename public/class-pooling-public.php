<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://indie.systems/pooling-plugin
 * @since      1.0.0
 *
 * @package    PLG
 * @subpackage PLG/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    PLG
 * @subpackage PLG/public
 * @author     Chris Dimas <info@indie.systems>
 */
class PLG_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $pooling    The ID of this plugin.
     */
    private $pooling;

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
     * @param      string    $pooling       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($pooling, $version)
    {

        $this->pooling = $pooling;
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
         * defined in PLG_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The PLG_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->pooling . 'fawe', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->pooling, plugin_dir_url(__FILE__) . 'css/pooling-public.css', array(), $this->version, 'all');
        wp_enqueue_style($this->pooling . '_select2', plugin_dir_url(__FILE__) . 'css/select2.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in PLG_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The PLG_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        $gapikey = get_option('pooling_gmaps_api',null);
        $url = "https://maps.googleapis.com/maps/api/js?key={$gapikey}&language=el&&libraries=places,geometry";
        wp_enqueue_script($this->pooling . '_gmapautocomplete', plugin_dir_url(__FILE__) . 'js/gmap-autocomplete.js', array('jquery',), $this->version, false);
        wp_enqueue_script($this->pooling . '_gmaps', $url, array('jquery'), $this->version, false);
        wp_enqueue_script($this->pooling, plugin_dir_url(__FILE__) . 'js/pooling-public.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->pooling . '_select_s2', plugin_dir_url(__FILE__) . 'js/select2/select2.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->pooling . '_bootstrap4', '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.bundle.js', array('jquery'), $this->version, false);
        wp_register_script($this->pooling . '_send_rq', plugin_dir_url(__FILE__) . 'js/send-request.js', array('jquery'), $this->version, false);
        wp_register_script($this->pooling . '_withdraw', plugin_dir_url(__FILE__) . 'js/withdraw.js', array('jquery'), $this->version, false);
        wp_register_script($this->pooling . '_report', plugin_dir_url(__FILE__) . 'js/report.js', array('jquery'), $this->version, false);
        wp_register_script($this->pooling . '_accept', plugin_dir_url(__FILE__) . 'js/accept.js', array('jquery'), $this->version, false);
        wp_register_script($this->pooling . '_map_script', plugin_dir_url(__FILE__) . 'js/map.js', array('jquery'), $this->version, false);
        wp_register_script($this->pooling . '_registration', plugin_dir_url(__FILE__) . 'js/registration.js', array('jquery'), $this->version, false);
    }

    public function replace_script_att($tag, $handle)
    {
        if ($this->pooling . '_gmaps' !== $handle) {
            return $tag;
        }
        return str_replace(' src', ' async defer src', $tag);
    }

    public function not_logged_in_html()
    {
        if (!is_user_logged_in()) {
            echo '<h2>' . __('Please login first', 'pooling') . '</h2>';
            echo '<a href="/login" class="button">' . __('Login') . '</a>';
            exit;
            return false;
        }
    }

    public function main_map($atts)
    {
        $this->not_logged_in_html();
        $user = wp_get_current_user();
        // set the ajax script global data for this page
        $main_map_nonce = wp_create_nonce('main-map');
        wp_localize_script($this->pooling . '_send_rq', 'pooling_map_global_send_request', array(
            'ajax_url'              => admin_url('admin-ajax.php'),
            'nonce'                 => $main_map_nonce,
            'modal_title'           => __('Send Aid Offer to User', 'pooling'),
            'aid_request_done'      => __('Done! Your Offer was sent!', 'pooling'),
            'aid_request_exists'    => __('You have already sent an aid offer!', 'pooling'),
            'send_button_text'      => __('Send request', 'pooling'),
            'send_button_text_done' => __('Done!', 'pooling'),
            'send_button_text_fail' => __('Not send!', 'pooling'),
            'send_button_text'      => __('Send request', 'pooling'),
            'user_id'               => $user->ID,
            'user_login'            => $user->user_login,
        ));
        wp_enqueue_script($this->pooling . '_send_rq');
        $center   = [];
        $center   = get_user_meta($user->ID, 'latlng', true);
        $longlats = [];
        global $wpdb;
        $usr            = new \PLGLib\User;
        $country        = get_user_meta($user->ID, 'country', true);
        $user_ids       = $wpdb->get_results("SELECT user_id FROM {$wpdb->prefix}usermeta WHERE meta_key='country' AND meta_value='{$country}'", ARRAY_A);
        $user_ids       = array_column($user_ids, 'user_id');
        $longlats       = \PLGLib\CouchDB::connect()->key($country)->getView('countries', 'countries-view-verified')->rows;
        $users_longlats = [];
        foreach ($longlats as $key => $azi) {
            if (\PLGLib\Geo::distanceGeoPoints($center['lat'], $center['lng'], $azi->value->lat, $azi->value->lng) <= POOLING_RADIUS) {
                $users_longlats[] = $azi->value;
            } else {
                unset($longlats[$key]);
            }
        }
        wp_localize_script($this->pooling . '_send_rq', 'pooling_map_global', array(
            'ajax_url'              => admin_url('admin-ajax.php'),
            'nonce'                 => $main_map_nonce,
            'users_longlats'               => json_encode($users_longlats),
            'center'            => json_encode($center),
            'pooling_radius'            => POOLING_RADIUS,
        ));
        wp_enqueue_script($this->pooling . '_map_script');
        include 'partials/map.php';
    }

    public function my_requests($atts)
    {
        $this->not_logged_in_html();
        $user = wp_get_current_user();
        $usr  = new \PLGLib\User;
        // set the ajax script global data for this page
        $_nonce = wp_create_nonce('aid-requests');

        // Withdraw Script
        wp_localize_script($this->pooling . '_withdraw', 'pooling_map_global', array(
            'ajax_url'              => admin_url('admin-ajax.php'),
            'nonce'                 => $_nonce,
            'modal_title'           => __('Withdraw Aid Offer for User', 'pooling'),
            'aid_request_done'      => __('Done! Your Request was sent!', 'pooling'),
            'aid_request_exists'    => __('You have already withdrew your offer!', 'pooling'),
            'send_button_text'      => __('Submit', 'pooling'),
            'send_button_text_done' => __('Done!', 'pooling'),
            'send_button_text_fail' => __('Failed!', 'pooling'),
            'user_id'               => $user->ID,
            'user_login'            => $user->user_login,
        ));
        wp_enqueue_script($this->pooling . '_withdraw');

        // Report Script
        wp_localize_script($this->pooling . '_report', 'pooling_map_global', array(
            'ajax_url'              => admin_url('admin-ajax.php'),
            'nonce'                 => $_nonce,
            'modal_title'           => __('Withdraw Aid Offer for User', 'pooling'),
            'aid_request_done'      => __('Done! Your report was sent!', 'pooling'),
            'aid_request_exists'    => __('You have already reported this!', 'pooling'),
            'send_button_text'      => __('Submit', 'pooling'),
            'send_button_text_done' => __('Done!', 'pooling'),
            'send_button_text_fail' => __('Failed!', 'pooling'),
            'user_id'               => $user->ID,
            'user_login'            => $user->user_login,
        ));
        wp_enqueue_script($this->pooling . '_report');

        // Accept Script
        wp_localize_script($this->pooling . '_accept', 'pooling_map_global', array(
            'ajax_url'              => admin_url('admin-ajax.php'),
            'nonce'                 => $_nonce,
            'modal_title'           => __('Accept Aid Offer from User', 'pooling'),
            'aid_request_done'      => __('Done!', 'pooling'),
            'aid_request_exists'    => __('You have already accepted this offer!', 'pooling'),
            'send_button_text'      => __('Submit', 'pooling'),
            'send_button_text_done' => __('Done!', 'pooling'),
            'send_button_text_fail' => __('Failed!', 'pooling'),
            'user_id'               => $user->ID,
            'user_login'            => $user->user_login,
        ));
        wp_enqueue_script($this->pooling . '_accept');
        $my_aid_bids = \PLGLib\CouchDB::connect()
            ->startkey([$user->ID, '{}'])->endkey([$user->ID])
            ->descending(true)->getView('aidRequests', 'aid-requests-user')->rows;
        $my_aid_requests = \PLGLib\CouchDB::connect()
            ->startkey([$user->ID, '{}'])->endkey([$user->ID])
            ->descending(true)->getView('aidRequests', 'aid-requests-target')->rows;
        $my_aid_requests = array_merge($my_aid_requests, $my_aid_bids);
        include 'partials/requests.php';
    }

    public function show_error_messages()
    {
        if ($codes = $this->errors()->get_error_codes()) {
            echo '<div class="alert alert-danger">';
            // Loop error codes and display errors
            foreach ($codes as $code) {
                $message = $this->errors()->get_error_message($code);
                echo '<strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
            }
            echo '</div>';
        }
    }

    public function errors()
    {
        static $wp_error; // Will hold global variable safely
        return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
    }

    public function get_user_meta($meta_name, $user = null)
    {
        return isset($user->ID) ? get_user_meta($user->ID, $meta_name, true) : null;
    }

    public function registration_form($user = null)
    {
        wp_enqueue_script($this->pooling . '_gmapautocomplete');
        wp_enqueue_script($this->pooling . '_registration');
        ob_start();
        // only show the registration form to non-logged-in members
        if (!is_user_logged_in()) {
            // check to make sure user registration is enabled
            $registration_enabled = get_option('users_can_register');
            // only show the registration form if allowed
            if ($registration_enabled) {
                $this->show_error_messages();
                include_once 'partials/registration.php';
                return ob_get_clean();
            } else {
                $output = __('User registration is not enabled','pooling');
            }
            return $output;
        }
    }

    public function user_profile_form($user = null)
    {
        $this->not_logged_in_html();
        if (!$user) {
            $user = wp_get_current_user();
        }
        ob_start();
        $usr = new PLGLib\User;
        $usr->update_user_form($user->ID);
        include_once 'partials/account.php';
        return ob_get_clean();
    }

    public function add_new_member()
    {
        if (isset($_POST["pooling_action"]) && $_POST["pooling_action"] === 'register_account' && wp_verify_nonce($_POST['pooling_register_nonce'], 'pooling-register-nonce')) {

            $usr = new \PLGLib\User;
            if (username_exists($_POST['user_login'])) {
                // Username already registered
                $this->errors()->add('username_unavailable', __('Username already taken'));
            }
            if (!validate_username($_POST['user_login'])) {
                // invalid username
                $this->errors()->add('username_invalid', __('Invalid username'));
            }
            if (!is_email($_POST['user_email'])) {
                //invalid email
                $this->errors()->add('email_invalid', __('Invalid email'));
            }
            if (email_exists($_POST['user_email'])) {
                //Email address already registered
                $this->errors()->add('email_used', __('Email already registered'));
            }
            if ($_POST['user_password'] == '') {
                // passwords do not match
                $this->errors()->add('password_empty', __('Please enter a password'));
            }
            if ($_POST['user_password'] != $_POST['password_confirm']) {
                // passwords do not match
                $this->errors()->add('password_mismatch', __('Passwords do not match'));
            }

            // wp_get_password_hint()
            $usr->validation();
            $wp_error = $this->errors();
            $usr->user_update_errors_registration($wp_error);
            $errors = $wp_error->get_error_messages();
            // only create the user in if there are no errors
            if (empty($errors)) {

                $new_user_id = wp_insert_user(array(
                    'user_login'      => $_POST['user_login'],
                    'user_pass'       => $_POST['user_password'],
                    'user_email'      => $_POST['user_email'],
                    'first_name'      => $_POST['first_name'],
                    'last_name'       => $_POST['last_name'],
                    'user_registered' => date('Y-m-d H:i:s'),
                    'role'            => 'subscriber',
                )
                );
                if ($new_user_id) {
                    // send an email to the admin alerting them of the registration
                    wp_new_user_notification($new_user_id);
                    $usr->update_user_fields_registration($new_user_id);
                    do_action('pooling_after_update_meta', $new_user_id);
                    // send the newly created user to the home page after logging them in
                    switch (get_option('pooling_verification_method',null) ?: POOLING_VERIFICATION_METHOD) {
                        case 'email':
                            wp_redirect('/login');
                            break;
                        case 'sms':
                            wp_redirect('/verify-code?id=' . $new_user_id);
                            break;
                        default:
                            wp_redirect('/login');
                            break;
                    }
                    exit;
                }

            }

        }
    }

    public function login_form()
    {
        ob_start();?>
        <?php
$this->show_error_messages();
        include 'partials/login.php';
        ?>
        <?php
return ob_get_clean();
    }

    public function verify_code()
    {
        ob_start();?>
            <?php
// show any error messages after form submission
        $this->show_error_messages();
        ?>
        <div class="login-form">
        <form id="pooling_login_form"  class="pooling_form"action="" method="post">
            <fieldset>
                <p>
                    <label for="pooling_user_vericode"><?php _e('Enter your sms code', 'pooling');?></label>
                    <input name="pooling_user_vericode" id="pooling_user_vericode" class="required" type="text" required="required"/>
                    <input name="pooling_user_id" id="pooling_user_id" class="required" type="hidden" value="<?php echo esc_attr($_REQUEST['id'] ?? ''); ?>"/>
                    <input type="hidden" name="pooling_login_nonce" value="<?php echo wp_create_nonce('pooling-login-vericode-nonce'); ?>"/>
                    <input type="hidden" name="pooling_action" value="pooling-verify-code"/>
                </p>
                <p>
                    <input id="pooling_login_submit" type="submit" value="<?php _e('Verify', 'pooling');?>"/>
                </p>
            </fieldset>
        </form>
        </div>
        <?php
return ob_get_clean();
    }

    public function login_member()
    {
        if (isset($_POST['pooling_user_login']) && wp_verify_nonce($_POST['pooling_login_nonce'], 'pooling-login-nonce')) {

            // this returns the user ID and other info from the user name
            $user = get_user_by('login', $_POST['pooling_user_login']);

            if (!$user) {
                // if the user name doesn't exist
                $this->errors()->add('empty_username', __('Invalid username'));
                return false;
            }

            if (!isset($_POST['pooling_user_pass']) || $_POST['pooling_user_pass'] == '') {
                // if no password was entered
                $this->errors()->add('empty_password', __('Please enter a password'));
                return false;
            }

            // check the user's login with their password
            if (!wp_check_password($_POST['pooling_user_pass'], $user->user_pass, $user->ID)) {
                // if the password is incorrect for the specified user
                $this->errors()->add('empty_password', __('Incorrect password'));
                return false;
            }
            $usr    = new \PLGLib\User;
            $vcheck = $usr->check_user_verified($user);
            if (is_wp_error($vcheck)) {
                $this->errors()->add('not_verified', __('The account is not verified', 'pooling'));
            }

            // retrieve all error messages
            $errors = $this->errors()->get_error_messages();

            // only log the user in if there are no errors
            if (empty($errors)) {
                $usr->logon($user->ID, $user->user_login);
                do_action('wp_login', $_POST['pooling_user_login']);
                wp_redirect('/map');exit;
            }
        }
    }

    public function remove_admin_bar()
    {
        if (!current_user_can('administrator') && !is_admin()) {
            show_admin_bar(false);
        }
    }

    public function get_needs()
    {
        if (isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id']) && is_user_logged_in() && check_ajax_referer('main-map')) {
            $_REQUEST['user_id'] = (int) $_REQUEST['user_id'];
            $needs               = array_fill_keys(get_user_meta($_REQUEST['user_id'], 'needs', true), 'nothing');
            wp_send_json(array_intersect_key(PLGLib\StaticOptions::get_needs(), $needs));
        }
    }

    public function get_offers()
    {
        if (isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id']) && is_user_logged_in() && is_numeric($_REQUEST['user_id'])) {
            $_REQUEST['user_id'] = (int) $_REQUEST['user_id'];
            $offers              = array_fill_keys(get_user_meta($_REQUEST['user_id'], 'offers', true), 'nothing');
            wp_send_json(array_intersect_key(PLGLib\StaticOptions::get_offers(), $offers));
        }
    }

    public function aid_request()
    {
        if (isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id']) && is_user_logged_in() && check_ajax_referer('main-map')) {
            $request = new PLGLib\Request;
            try {
                $req = $request->send_request(
                    wp_get_current_user()->ID,
                    (int) $_REQUEST['user_id'],
                    array_column($_REQUEST['needs'], 'value')
                );
                if ($req) {
                    wp_send_json($req);
                } else {
                    throw new \Exception(__('You have already sent an aid request!', 'pooling'), 403);
                }
            } catch (\Exception $e) {
                $error = new \WP_Error();
                $error->add('aid_req_error', __('<strong>ERROR</strong>: ' . $e->getMessage(), 'pooling'));
                wp_send_json_error($error, $e->getCode());
            }
        }
    }

    public function get_aid_request_id()
    {
        if (isset($_REQUEST['aid_request_id']) && !empty($_REQUEST['aid_request_id']) && is_user_logged_in() && check_ajax_referer('aid-requests')) {
            $user_id = wp_get_current_user()->ID;
            $request = new PLGLib\Request;
            try {
                $aid_req = $request->get_request(esc_attr($_REQUEST['aid_request_id']));
                $can_get = $aid_req->user_id === $user_id || $aid_req->target_user_id === $user_id;
                if ($can_get) {
                    wp_send_json($aid_req->fields);
                } else {
                    throw new \Exception(__('You are not authorized to view this request!', 'pooling'), 403);
                }
            } catch (\Exception $e) {
                $error = new \WP_Error();
                $error->add('aid_req_error', __('<strong>ERROR</strong>: ' . $e->getMessage(), 'pooling'));
                wp_send_json_error($error, $e->getCode());
            }
        }
    }

    public function aid_request_withdraw()
    {
        if (isset($_REQUEST['aid_request_id']) && !empty($_REQUEST['aid_request_id']) && is_user_logged_in() && check_ajax_referer('aid-requests')) {
            $user_id = wp_get_current_user()->ID;
            $request = new PLGLib\Request;
            try {
                $aid_req = $request->withdraw_request(esc_attr($_REQUEST['aid_request_id']), $user_id);
                wp_send_json($aid_req->fields);
            } catch (\Exception $e) {
                $error = new \WP_Error();
                $error->add('aid_req_error', __('<strong>ERROR</strong>: ' . $e->getMessage(), 'pooling'));
                wp_send_json_error($error, $e->getCode());
            }
        }
    }

    public function aid_request_accept()
    {
        if (isset($_REQUEST['aid_request_id']) && !empty($_REQUEST['aid_request_id']) && is_user_logged_in() && check_ajax_referer('aid-requests')) {
            $user_id = wp_get_current_user()->ID;
            $request = new PLGLib\Request;
            try {
                $aid_req = $request->accept_request(esc_attr($_REQUEST['aid_request_id']), $user_id);
                wp_send_json($aid_req->fields);
            } catch (\Exception $e) {
                $error = new \WP_Error();
                $error->add('aid_req_error', __('<strong>ERROR</strong>: ' . $e->getMessage(), 'pooling'));
                wp_send_json_error($error, $e->getCode());
            }
        }
    }

}
