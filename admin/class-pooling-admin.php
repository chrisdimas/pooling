<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://indie.systems/pooling-plugin
 * @since      1.0.0
 *
 * @package    PLG
 * @subpackage PLG/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    PLG
 * @subpackage PLG/admin
 * @author     Chris Dimas <info@indie.systems>
 */
class PLG_Admin
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
     * @param      string    $pooling       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($pooling, $version)
    {

        $this->pooling = $pooling;
        $this->version = $version;
        $this->tabs    = new WP_Tabs();

    }

    /**
     * Register the stylesheets for the admin area.
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

        wp_enqueue_style($this->pooling, plugin_dir_url(__FILE__) . 'css/pooling-admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->pooling . '_select2', plugin_dir_url(__FILE__) . 'css/select2.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
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
        wp_enqueue_script($this->pooling . '_gmaps', $url, array('jquery'), $this->version, false);
        wp_enqueue_script($this->pooling, plugin_dir_url(__FILE__) . 'js/pooling-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->pooling . '_gmapautocomplete', plugin_dir_url(__FILE__) . 'js/gmap-autocomplete.js', array('jquery', $this->pooling . '_gmaps'), $this->version, false);
        wp_enqueue_script($this->pooling . '_select2', plugin_dir_url(__FILE__) . 'js/select2/select2.min.js', array('jquery'), $this->version, false);

    }

    public function add_user_column_verified_header($column_headers)
    {
        $column_headers['is_verified'] = __('Verified', 'pooling');
        return $column_headers;
    }

    public function add_user_column_verified_content($value, $column_name, $user_id)
    {
        if ($column_name == 'is_verified') {
            return get_user_meta($user_id, 'user_verified', true) == 1 ? '<span class="dashicons dashicons-yes-alt is-verified-badge"></span>' : '<span class="dashicons dashicons-dismiss is-verified-badge"></span>';
        }
        return $value;
    }

    public function get_user_meta($meta_name, $user = null)
    {
        return isset($user->ID) ? get_the_author_meta($meta_name, $user->ID) : null;
    }

    public function extra_user_profile_fields($user = null)
    {
        ?>
    <h3><?php _e("Extra profile information", "blank");?></h3>
    <table class="form-table">
    <tr>
        <th><label for="phone"><?php _e("Phone");?></label></th>
        <td>
            <input type="text" name="phone" id="phone" value="<?php echo esc_attr($this->get_user_meta('phone', $user)); ?>" class="regular-text" required="required"/><br />
            <span class="description"><?php _e("Please enter your phone.");?></span>
        </td>
    </tr>
    <tr>
        <th><label for="mobile"><?php _e("Mobile");?></label></th>
        <td>
            <input type="text" name="mobile" id="mobile" value="<?php echo esc_attr($this->get_user_meta('mobile', $user)); ?>" class="regular-text" required="required"/><br />
            <span class="description"><?php _e("Please enter your mobile.");?></span>
        </td>
    </tr>
    <tr>
        <th><label for="year_birth"><?php _e("Year of birth");?></label></th>
        <td>
            <input type="number" min="1900" max="2020" ste="1" name="year_birth" id="year_birth" value="<?php echo esc_attr($this->get_user_meta('year_birth', $user)); ?>" class="regular-text" required="required"/><br />
            <span class="description"><?php _e("Please enter year birth.");?></span>
        </td>
    </tr>
    <tr>
        <th><label for="address"><?php _e("Address");?></label></th>
        <td>
            <input type="text" name="address" id="address" value="<?php echo esc_attr($this->get_user_meta('address', $user)); ?>" class="regular-text" required="required"/><br />
            <span class="description"><?php _e("Please enter your address.");?></span>
        </td>
    </tr>
    <tr>
    <th><label for="postalcode"><?php _e("Postal Code");?></label></th>
        <td>
            <input type="text" name="postalcode" id="postalcode" value="<?php echo esc_attr($this->get_user_meta('postalcode', $user)); ?>" class="regular-text" required="required"/><br />
            <span class="description"><?php _e("Please enter your postal code.");?></span>
        </td>
    </tr>
    <tr>
        <th><label for="city"><?php _e("City");?></label></th>
        <td>
            <input type="text" name="city" id="city" value="<?php echo esc_attr($this->get_user_meta('city', $user)); ?>" class="regular-text" required="required"/><br />
            <span class="description"><?php _e("Please enter your city.");?></span>
        </td>
    </tr>
    <tr>
        <th><label for="state"><?php _e("State/Provnce");?></label></th>
        <td>
            <input type="text" name="state" id="state" value="<?php echo esc_attr($this->get_user_meta('state', $user)); ?>" class="regular-text" required="required"/><br />
            <span class="description"><?php _e("Please enter State/Provnce.");?></span>
        </td>
    </tr>
    <tr>
        <th><label for="country"><?php _e("Country");?></label></th>
        <td>
            <select name="country" id="country" required="required"><?php echo \PLGLib\StaticOptions::get_countries_html($this->get_user_meta('country', $user)); ?></select><br />
            <span class="description"><?php _e("Please enter your country.");?></span>
        </td>
    </tr>
    <tr>
        <th><label for="profession"><?php _e("Profession");?></label></th>
        <td>
            <select name="profession" class="" id="profession" required="required"><?php
echo \PLGLib\StaticOptions::get_profession_options_html($this->get_user_meta('profession', $user));
        ?></select>
            <br />
            <span class="description"><?php _e("Please enter your profession.");?></span>
        </td>
    </tr>
    <tr>
        <th><label for="own_transport"><?php _e("Own transport mean");?></label></th>
        <td>
            <input type="checkbox" name="own_transport" value="1" <?php checked($this->get_user_meta('own_transport', $user));?>>
            <br />
            <span class="description"><?php _e("Please enter your profession.");?></span>
        </td>
    </tr>
    <input type="hidden" name="lng" id="lng" value="<?php echo esc_attr($this->get_user_meta('lng', $user)); ?>"/>
    <input type="hidden" name="lat" id="lat" value="<?php echo esc_attr($this->get_user_meta('lat', $user)); ?>"/>
    <input type="hidden" name="map_url" id="map_url" value="<?php echo esc_attr($this->get_user_meta('map_url', $user)); ?>"/>
    <tr>
        <th><?php _e("Needs", 'pooling');?></th>
        <td>
            <select name="needs[]" id="needs" class="select2" multiple="multiple">
            <?php echo \PLGLib\StaticOptions::get_needs_html($this->get_user_meta('needs', $user)); ?>
            </select>
        </td>
    </tr>
    <tr>
        <th><?php _e("Offers", 'pooling');?></th>
        <td>
            <select name="offers[]" id="offers" class="select2" multiple="multiple">
            <?php echo \PLGLib\StaticOptions::get_offers_html($this->get_user_meta('offers', $user)); ?>
            </select>
        </td>
    </tr>
    </table>
<?php }

    public function add_menu()
    {
        $parent_slug = 'pooling';
        add_menu_page(
            __('Pooling', 'pooling'),
            __('Pooling', 'pooling'),
            'manage_options',
            $parent_slug,
            [$this, 'display_admin_page'],
            'dashicons-location-alt',
            2
        );
    }
    
    public function display_admin_page()
    {
        global $plugin_page;
        $this->tabs->add_tab(
            $plugin_page,
            'settings',
            __('Settings', 'pooling'),
            'manage_options',
            function () {include_once (plugin_dir_path(__FILE__) . 'partials/settings.php');}
        );
        $this->tabs->display();
    }
}
