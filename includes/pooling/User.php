<?php
namespace PLGLib;

use PHPOnCouch\CouchDocument;

/**
 * User Stuff
 */
class User
{
    static $errors_pass_hook = [];
    const POOLING_VERIFICATION_METHOD = 'email';

    public function validation()
    {
        $fields  = ['year_birth', 'address', 'postalcode', 'city', 'state', 'country', 'profession', 'mobile'];
        $empties = [
            __('birth date'),
            __('address'),
            __('postal code'),
            __('city'),
            __('state'),
            __('country'),
            __('profession'),
            __('phone'),
            __('mobile'),
        ];
        $min_years_old = 18;
        $max_years_old = 150;
        $max_year      = (int) date('Y') - $min_years_old;
        $min_year      = (int) date('Y') - $max_years_old;

        foreach ($fields as $key => $value) {
            if (empty($_POST[$value])) {
                $msg                      = sprintf('%s %s.', __('Please insert', 'pooling'), $empties[$key]);
                self::$errors_pass_hook[] = [$value . '_error', sprintf('<strong>%s</strong> %s', __('Error'), $msg)];
            }
        }
        if (!empty($_POST['year_birth']) && intval($_POST['year_birth']) < $min_year && intval($_POST['year_birth']) <= $max_year) {
            $msg                      = sprintf(__('You have to be older than %d years and younger than %d years', 'pooling'), $min_years_old, $max_years_old);
            self::$errors_pass_hook[] = ['year_brth_error_2', sprintf('%s', $msg)];
        }
        if(!$this->is_unique_mobile($_POST['mobile'])){
            self::$errors_pass_hook[] = ['mobile_unique_error', __('This mobile number already exists.','pooling')];
        }
        if(!is_numeric($_POST['mobile'])){
            self::$errors_pass_hook[] = ['mobile_na_error', __('Mobile is not valid.','pooling')];
        }
        // if(isset($_POST['phone']) && !is_numeric($_POST['phone'])){
        //     self::$errors_pass_hook[] = ['phone_na_error', __('Phone is not valid.','pooling')];
        // }
        return count(self::$errors_pass_hook) > 0 ? false : true;
    }

    public function update_user_profile_fields($user_id)
    {
        if (!$this->validation()) {
            return false;
        }
        update_user_meta($user_id, 'year_birth', $_POST['year_birth']);
        update_user_meta($user_id, 'address', $_POST['address']);
        update_user_meta($user_id, 'postalcode', $_POST['postalcode']);
        update_user_meta($user_id, 'city', $_POST['city']);
        update_user_meta($user_id, 'state', $_POST['state']);
        update_user_meta($user_id, 'country', $_POST['country']);
        update_user_meta($user_id, 'phone', $_POST['phone']);
        update_user_meta($user_id, 'mobile', $_POST['mobile']);
        update_user_meta($user_id, 'profession', $_POST['profession']);
        update_user_meta($user_id, 'own_transport', $_POST['own_transport']);
        update_user_meta($user_id, 'lng', $_POST['lng']);
        update_user_meta($user_id, 'lat', $_POST['lat']);
        update_user_meta($user_id, 'latlng', ['lat' => (float)$_POST['lat'], 'lng' => (float)$_POST['lng']]);
        update_user_meta($user_id, 'map_url', $_POST['map_url']);
        update_user_meta($user_id, 'needs', $_POST['needs'] ?? []);
        update_user_meta($user_id, 'offers', $_POST['offers'] ?? []);
        $doc_id = CouchDB::get_user_doc_id($user_id);
        if (!$_doc = CouchDB::get($doc_id)) {
            $doc                = new CouchDocument(CouchDB::connect());
            $doc->_id           = $doc_id;
            $doc->user_id       = $user_id;
            $doc->type          = 'user';
            $doc->lat           = (float) $_POST['lat'];
            $doc->lng           = (float) $_POST['lng'];
            $doc->country       = $_POST['country'];
            $doc->needs         = $_POST['needs'] ?? [];
            $doc->offers        = $_POST['offers'] ?? [];
            $doc->own_transport = (int) $_POST['own_transport'];
        } else {
            $_doc->type          = 'user';
            $_doc->lat           = (float) $_POST['lat'];
            $_doc->lng           = (float) $_POST['lng'];
            $_doc->country       = $_POST['country'];
            $_doc->needs         = $_POST['needs'] ?? [];
            $_doc->offers        = $_POST['offers'] ?? [];
            $_doc->own_transport = (int) $_POST['own_transport'];
        }
    }

    public function get_user($user_id)
    {
        $doc_id = CouchDB::get_user_doc_id($user_id);
        return CouchDB::get($doc_id);
    }

    public function update_user_fields($user_id)
    {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        $this->update_user_fields($user_id);
    }

    public function update_user_form($user_id)
    {
        if (isset($_POST['pooling_action']) && $_POST['pooling_action'] === 'update_account') {
            $this->update_user_profile_fields($user_id);
        }
    }

    public function update_user_fields_registration($user_id)
    {
        $this->update_user_fields($user_id);
    }

    public function set_user_not_verified($user_id)
    {
        update_user_meta($user_id, 'user_verified', 0);
        $doc                = CouchDB::get(CouchDB::get_user_doc_id($user_id));
        $doc->user_verified = 0;
        debug_print_backtrace();
    }

    public function set_user_verified($user_id)
    {
        update_user_meta($user_id, 'user_verified', 1);
        $doc                = CouchDB::get(CouchDB::get_user_doc_id($user_id));
        $doc->user_verified = 1;
    }

    public function is_user_verified($user_id)
    {
        $is_verfied = get_user_meta($user_id, 'user_verified', true);
        return $is_verfied == 1 ? true : false;
    }

    public function verification_start($user_id)
    {
        $this->set_user_not_verified($user_id);

        switch ( get_option('pooling_verification_method',null) ?: POOLING_VERIFICATION_METHOD ?? self::POOLING_VERIFICATION_METHOD) {
            case 'email':
                $this->email_verification_start($user_id);
                break;
            case 'sms':
                $this->sms_verification_start($user_id);
                break;
            default:
                $this->email_verification_start($user_id);
                break;
        }
    }

    private function email_verification_start($user_id)
    {
        // get user data
        $user_info = get_userdata($user_id);
        // create md5 code to verify later
        $code = md5(AUTH_SALT . time());
        // make it into a code to send it to user via email
        $string = array('id' => $user_id, 'code' => $code);
        update_user_meta($user_id, 'verification_code', $code);
        // create the url
        $url = get_site_url() . '/?act=' . base64_encode(serialize($string));
        // basically we will edit here to make this nicer
        $html = sprintf('%s <br/><br/> <a href="%s">%s</a>',
            __('Please click the following link', 'pooling'),
            $url,
            $url
        );
        // send an email out to user
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail($user_info->user_email, __('User Account Verification', 'pooling'), $html, $headers);
    }

    private function sms_verification_start($user_id)
    {
        $sms  = new Sms;
        $code = mt_rand(100000, 999999);
        update_user_meta($user_id, 'verification_code', $code);
        update_user_meta($user_id, 'verification_code_last', time());
        $mobile = get_user_meta($user_id, 'mobile', true);
        // $to     = StaticOptions::get_phone_prefixes()[get_user_meta($user_id, 'country', true)] . $mobile;
        $to     = StaticOptions::get_phone_prefixes()[get_user_meta($user_id, 'country', true)] . $mobile;
        $sms_id = $sms->message($to, sprintf('%s %d', __('Verifcation code', 'pooling'), $code));
        update_user_meta($user_id, 'sms_id', $sms_id);
    }

    public function verify_user_code()
    {
        switch (get_option('pooling_verification_method', null) ?: POOLING_VERIFICATION_METHOD ?? self::POOLING_VERIFICATION_METHOD) {
            case 'email':
                $this->verify_user_code_email();
                break;
            case 'sms':
                $this->verify_user_code_sms();
                break;
            default:
                $this->verify_user_code_email();
                break;
        }
    }

    private function verify_user_code_email()
    {
        if (isset($_GET['act'])) {
            $data       = unserialize(base64_decode($_GET['act']));
            $data['id'] = intval($data['id']);
            $code       = get_user_meta($data['id'], 'verification_code', true);
            // verify whether the code given is the same as ours
            if ($code == $data['code'] && !empty($code)) {
                // update the user meta
                $this->set_user_verified($data['id']);
                // delete vericode
                update_user_meta($data['id'], 'verification_code', null);
                // wc_add_notice(__('<strong>Success:</strong> Your account has been activated! ', 'pooling'));
                $this->logon($data['id'], get_userdata($data['id'])->user_login);
                wp_redirect('/map');
            }
        }
    }

    private function verify_user_code_sms()
    {
        $is_vericode = isset($_REQUEST['pooling_user_vericode']) && !empty($_REQUEST['pooling_user_vericode']);
        $is_user_id  = isset($_REQUEST['pooling_user_id']) && !empty($_REQUEST['pooling_user_id']) && is_numeric($_REQUEST['pooling_user_id']);
        $is_action   = isset($_POST['pooling_action']) && $_POST['pooling_action'] == 'pooling-verify-code';
        $is_nonce    = isset($_POST['pooling_login_nonce']) && wp_verify_nonce($_POST['pooling_login_nonce'], 'pooling-login-vericode-nonce');
        if ($is_vericode && $is_user_id && $is_action && $is_nonce) {
            $_REQUEST['pooling_user_vericode'] = sanitize_text_field($_REQUEST['pooling_user_vericode']);
            $_REQUEST['pooling_user_id']       = intval($_REQUEST['pooling_user_id']);
            $code                              = get_user_meta($_REQUEST['pooling_user_id'], 'verification_code', true);
            // verify whether the code given is the same as ours
            // if its empty its deleted or something's wrong
            if ($code == $_REQUEST['pooling_user_vericode'] && !empty($code)) {
                // update the user meta
                $this->set_user_verified($_REQUEST['pooling_user_id']);
                // delete vericode
                update_user_meta($_REQUEST['pooling_user_id'], 'verification_code', null);
                // wc_add_notice(__('<strong>Success:</strong> Your account has been activated! ', 'pooling'));
                $this->logon($_REQUEST['pooling_user_id'], get_userdata($_REQUEST['pooling_user_id'])->user_login);
                wp_redirect('/map');
            } else {
                wp_redirect('/verify-code/?id=' . intval($_REQUEST['pooling_user_id']));
            }
        }
    }

    public function check_user_verified($user)
    {
        $status = $this->is_user_verified($user->ID);
        if ($status == 0) {
            $errors = new \WP_Error();
            $errors->add('title_error', __('<strong>ERROR</strong>: This account has not been verified.', 'pooling'));
            return $errors;
        }
        return $user;
    }

    public function logon($user_id, $user_login)
    {
        wp_set_auth_cookie($user_id, true);
        wp_set_current_user($user_id, $user_login);
    }

    public function user_update_errors($errors)
    {
        if (count(self::$errors_pass_hook) > 0) {
            foreach (self::$errors_pass_hook as $key => $value) {
                $errors->add($value[0], $value[1]);
            }
        }
    }
    public function user_update_errors_registration(&$errors)
    {
        if (count(self::$errors_pass_hook) > 0) {
            foreach (self::$errors_pass_hook as $key => $value) {
                $errors->add($value[0], $value[1]);
            }
        }
    }

    public function is_unique_mobile($number)
    {
        global $wpdb;
        $phones = $wpdb->get_results("SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key='mobile' AND meta_value='{$number}'", ARRAY_A);
        if (count($phones) > 0) {
            if (isset($phones[0]['meta_value'])) {
                // handles user account mobile updates
                return $phones[0]['meta_value'] !== $number ? false : true;
            }
        }
        return  true;
    }
}
