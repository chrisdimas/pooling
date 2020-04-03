<?php
namespace PLGLib;

use PHPOnCouch\CouchDocument;
use PLGLib\Abstracts\RequestObject;

/**
 * Request Stuff
 */
class Request
{
    /**
     * @var string a prefix used in doc id
     */
    const REQ_PREFIX = 'aid_req_';

    public function create_doc_id($user_id, $target_user_id)
    {
        $prefix = defined('POOLING_COUCHDB_USER_PREFIX') ? POOLING_COUCHDB_REQ_PREFIX : self::REQ_PREFIX;
        $doc_id = $prefix . md5($user_id . $target_user_id . time());
        return $doc_id;
    }
    /**
     * Creates or returns existing aid request
     * @param  RequestObject $req [description]
     * @return [type]             [description]
     */
    public function create_request(RequestObject $req)
    {
        $doc_id = $this->create_doc_id($req->user_id, $req->target_user_id);
        if (!$_doc = CouchDB::get($doc_id)) {
            $doc      = new CouchDocument(CouchDB::connect());
            $doc->_id = $doc_id;
            foreach ($req as $prop => $value) {
                $doc->{$prop} = $value;
            }
            return $doc;
        } else {
            return false;
        }
    }

    /**
     * Get request document by using user id and target user id
     * @param  int $user_id
     * @param  int $target_user_id
     * @return PHPOnCouch\CouchDocument
     */
    public function get_request_user_target($user_id, $target_user_id)
    {
        $doc_id = $this->create_doc_id($req->user_id, $req->target_user_id);
        return CouchDB::get($doc_id);
    }

    /**
     * Get request document by using its ID
     * @param  string $req_id see   create_doc_id
     * @return PHPOnCouch\CouchDocument
     */
    public function get_request($req_id)
    {
        $doc = CouchDB::get($req_id);
        if (!$doc) {
            throw new \Exception(__('This request doesn\'t exist.', 'pooling'), 403);
        }
        return $doc;
    }

    public function can_send_request($user_id, $target_user_id, $needs_cover)
    {
        $user_requests = \PLGLib\CouchDB::connect()
            ->startkey([$user_id, $target_user_id, false, time() - POOLING_SEND_OFFER_WINDOW])
            ->endkey([$user_id, $target_user_id, false, time()])
            ->getView('aidRequests', 'aid-requests-both')->rows;
        if (count($user_requests) > 0) {
            throw new \Exception(sprintf(__('You can\'t send a second aid offer within %s hour(s)!', 'pooling'), Helpers::secs_to_hours(POOLING_SEND_OFFER_WINDOW)), 403);
        } elseif (empty($this->common_needs($user_id, $needs_cover))) {
            throw new \Exception(__('You can\'t cover something you don\'t offer!', 'pooling'), 403);
        } else {
            return true;
        }
    }

    public function common_needs($user_id, $needs_cover)
    {
        return array_intersect(get_user_meta($user_id, 'offers', true), $needs_cover);
    }

    /**
     * Sends an aid offer (request)
     * @param  int $user_id        WP user id
     * @param  int $target_user_id WP User id
     * @param  array $needs_cover    Needs array key
     * @return boolean|PHPOnCouch\CouchDocument
     */
    public function send_request($user_id, $target_user_id, $needs_cover)
    {
        if ($this->can_send_request($user_id, $target_user_id, $needs_cover)) {
            $r                    = new RequestObject;
            $r->user_id           = $user_id;
            $r->target_user_id    = $target_user_id;
            $r->needs_cover       = $this->common_needs($user_id, $needs_cover);
            $r->created_timestamp = time();
            $req                  = $this->create_request($r);
            do_action('pooling_send_request', $req);
            $this->offer_notification($req);
            return $req;
        } else {
            return false;
        }
    }

    /**
     * Check if user can withdraw the aid request
     * @param  PHPOnCouch\CouchDocument $doc
     * @param  int $supplied_user_id WP user id
     * @return boolean                   true if can or throw
     */
    public function can_withdraw_request($doc, $supplied_user_id)
    {
        if ($doc->user_id !== $supplied_user_id) {
            throw new \Exception(__('You are not authorized to withdraw this request!', 'pooling'), 403);
        } elseif (time() - $doc->created_timestamp > POOLING_ACCEPT_OFFER_WINDOW) {
            throw new \Exception(sprintf(__('You can withdraw the request within %s hour.', 'pooling'), Helpers::secs_to_hours(POOLING_SEND_OFFER_WINDOW)), 403);
        } elseif ($doc->target_accepted) {
            throw new \Exception(__('You can\'t withdraw the request once it\'s accepted.', 'pooling'), 403);
        } else {
            return true;
        }
    }

    /**
     * Withdraw the aid request
     * @param  string $request_id
     * @param  int $supplied_user_id WP user id
     * @return PHPOnCouch\CouchDocument
     */
    public function withdraw_request($request_id, $supplied_user_id)
    {
        $doc = $this->get_request($request_id);
        if ($this->can_withdraw_request($doc, $supplied_user_id)) {
            $doc->withdraw           = true;
            $doc->withdraw_timestamp = time();
            do_action('pooling_withdraw_request', $doc);
            $this->withdraw_notification($doc);
            return $doc;
        }
    }

    /**
     * Check if user can accept the aid offer(request)
     * @param  PHPOnCouch\CouchDocument $doc
     * @param  int $supplied_user_id WP user d
     * @return boolean                   True if can or throw
     */
    public function can_accept_request($doc, $supplied_user_id)
    {
        if ($doc->target_user_id !== $supplied_user_id) {
            throw new \Exception(__('You are not authorized to accept this request!', 'pooling'), 403);
            // } elseif (time() - $doc->created_timestamp > POOLING_ACCEPT_OFFER_WINDOW) {
            // throw new \Exception(__('You can accept the request within 1 hour.', 'pooling'), 403);
        } else {
            return true;
        }
    }

    /**
     * Mark request as accepted
     * @param  string $request_id
     * @param  int $supplied_user_id WP user id
     * @return PHPOnCouch\CouchDocument
     */
    public function accept_request($request_id, $supplied_user_id)
    {
        $doc = $this->get_request($request_id);
        if ($this->can_accept_request($doc, $supplied_user_id)) {
            $doc->target_accepted  = true;
            $doc->accept_timestamp = time();
            $usr                   = new User;
            $user_doc              = $usr->get_user($doc->target_user_id);
            // delete aid recipient needs from couchdb
            $user_doc->needs       = array_diff($user_doc->needs, $doc->needs_cover);
            $provider_user         = $usr->get_user($doc->user_id);
            // delete aid provider offers from couchdb
            $provider_user->offers = array_diff($provider_user->offers, $doc->needs_cover);
            // delete aid recipient needs from wp user meta
            update_user_meta($doc->target_user_id, 'needs', $user_doc->needs);
            // delete aid provider offers from wp user meta
            update_user_meta($doc->user_id, 'offers', $provider_user->offers);
            do_action('pooling_accept_request', $doc);
            $this->accept_notification($doc);
            return $doc;
        }
    }

    /**
     * Notify the two users about the acceptance of the offer
     * @param  striing $request_id
     * @return none
     */
    public function accept_notification(CouchDocument $req)
    {
        // Load twig
        $loader   = new \Twig\Loader\FilesystemLoader(POOLING_TEMPLATES_PATH);
        $twig     = new \Twig\Environment($loader, ['debug' => true]);
        $template = $twig->load('aid_request.html');

        $needs = Helpers::needs_to_label_array($req->needs_cover);
        // notify the aid provider for acceptance of the beneficiary user.
        $provider_data = get_userdata($req->user_id);
        $user_data     = get_userdata($req->target_user_id);
        $mobile        = get_user_meta($req->target_user_id, 'mobile', true);
        $address       = get_user_meta($req->target_user_id, 'address', true);
        $city          = get_user_meta($req->target_user_id, 'city', true);
        $zip           = get_user_meta($req->target_user_id, 'postalcode', true);
        $firstname     = get_user_meta($req->target_user_id, 'first_name', true);
        $lastname      = get_user_meta($req->target_user_id, 'last_name', true);

        $subject = sprintf(__('User %s has accepted your help!', 'pooling'), $user_data->user_login);
        $html    = $template->render([
            'header'   => $subject,
            'message'  => [
                __('A user has accepted your help! Please see your requests page to review it. It\'s mandatory to follow the rules of your national healthcare system and our platform rules.', 'pooling'),
                __('For your safety you need to call the user and verify all the information below', 'pooling'),
            ],
            'phone'    => __('Phone', 'pooling') . ': ' . $mobile,
            'fullname' => __('Fullname', 'pooling') . ': ' . $firstname . ' ' . $lastname,
            'username' => __('Username', 'pooling') . ': ' . $user_data->user_login,
            'address'  => __('Address', 'pooling') . ': ' . $address . ' ' . $city . ' ' . $zip,
            'needs'    => __('You offered', 'pooling') . ': ' . implode(',', $needs),
            'timings'  => [
                __('Offer time', 'pooling') . ': ' . wp_date(Helpers::date_format(), $req->created_timestamp),
                __('Accept time', 'pooling') . ': ' . wp_date(Helpers::date_format(), $req->accept_timestamp),
            ],
            'logo'     => 'http://covid19help.eu/wp-content/uploads/2020/03/logo128.png',
        ]);
        $this->email($provider_data->user_email, $subject, $html);

        $mobile    = get_user_meta($req->user_id, 'mobile', true);
        $address   = get_user_meta($req->user_id, 'address', true);
        $city      = get_user_meta($req->user_id, 'city', true);
        $zip       = get_user_meta($req->user_id, 'postalcode', true);
        $firstname = get_user_meta($req->user_id, 'first_name', true);
        $lastname  = get_user_meta($req->user_id, 'last_name', true);

        $subject = sprintf(__('You accepted an aid offer from %s!', 'pooling'), $provider_data->user_login);
        $html    = $template->render([
            'header'   => $subject,
            'message'  => [
                __('You accepted an aid offer! Please see your requests page to review it. It\'s mandatory to follow the rules of your national healthcare system and our platform rules.', 'pooling'),
                __('For your safety you need to call the user and verify all the information below', 'pooling'),
            ],
            'phone'    => __('Phone', 'pooling') . ': ' . $mobile,
            'fullname' => __('Fullname', 'pooling') . ': ' . $firstname . ' ' . $lastname,
            'username' => __('Username', 'pooling') . ': ' . $provider_data->user_login,
            'address'  => __('Address', 'pooling') . ': ' . $address . ' ' . $city . ' ' . $zip,
            'needs'    => __('The user offers', 'pooling') . ': ' . implode(',', $needs),
            'timings'  => [
                __('Offer time', 'pooling') . ': ' . wp_date(Helpers::date_format(), $req->created_timestamp),
                __('Accept time', 'pooling') . ': ' . wp_date(Helpers::date_format(), $req->accept_timestamp),
            ],
            'logo'     => 'http://covid19help.eu/wp-content/uploads/2020/03/logo128.png',
        ]);
        $this->email($user_data->user_email, $subject, $html);
        // notify the beneficiary that the help is coming and their phone was sent.
    }

    /**
     * Notify the two users about the aid offer
     * @param  string $request_id
     * @return none
     */
    public function offer_notification(CouchDocument $req)
    {
        // Load twig
        $loader   = new \Twig\Loader\FilesystemLoader(POOLING_TEMPLATES_PATH);
        $twig     = new \Twig\Environment($loader, ['debug' => true]);
        $template = $twig->load('offer_aid.html');

        $provider_data = get_userdata($req->user_id);
        $user_data     = get_userdata($req->target_user_id);

        $needs   = Helpers::needs_to_label_array($req->needs_cover);
        $subject = sprintf(__('User %s is offering help!', 'pooling'), $provider_data->user_login);

        $html = $template->render([
            'header'   => $subject,
            'message'  => __('A user is offering help! Please see your requests page to review it. The user will not know your personal information until you accept the offer. It\'s mandatory to follow the rules of your national healthcare system and our platform rules.', 'pooling'),
            'username' => __('Username', 'pooling') . ': ' . $provider_data->user_login,
            'needs'    => __('The user offers', 'pooling') . ': ' . implode(',', $needs),
            'logo'     => 'http://covid19help.eu/wp-content/uploads/2020/03/logo128.png',
        ]);
        $this->email($user_data->user_email, $subject, $html);

        $subject = sprintf(__('You offered help to User %s!', 'pooling'), $user_data->user_login);
        $html    = $template->render([
            'header'   => $subject,
            'message'  => sprintf(__('Please see your requests page to review it. The user will not know your personal information until accepts the offer. It\'s mandatory to follow the rules of your national healthcare system and our platform rules. Note: in case you want to wthdraw from your offer, you can do so within %s hour(s)', 'pooling'), Helpers::secs_to_hours(POOLING_ACCEPT_OFFER_WINDOW)),
            'username' => __('Username', 'pooling') . ': ' . $user_data->user_login,
            'needs'    => __('You offered', 'pooling') . ': ' . implode(',', $needs),
            'logo'     => 'http://covid19help.eu/wp-content/uploads/2020/03/logo128.png',
        ]);
        $this->email($provider_data->user_email, $subject, $html);
    }

    /**
     * Notify the two users about the aid offer withdrewal
     * @param  string $request_id
     * @return none
     */
    public function withdraw_notification(CouchDocument $req)
    {
        // Load twig
        $loader   = new \Twig\Loader\FilesystemLoader(POOLING_TEMPLATES_PATH);
        $twig     = new \Twig\Environment($loader, ['debug' => true]);
        $template = $twig->load('offer_aid.html');

        $provider_data = get_userdata($req->user_id);
        $user_data     = get_userdata($req->target_user_id);

        $needs   = Helpers::needs_to_label_array($req->needs_cover);
        $subject = sprintf(__('You withdrew the aid request for %s!', 'pooling'), $user_data->user_login);

        // render and send e-mail to the aid provder
        $html = $template->render([
            'header'   => $subject,
            'message'  => __('Please see your requests page to review it. Have in mind that other people are in need. Try not to withdraw aid offers.', 'pooling'),
            'username' => __('Username', 'pooling') . ': ' . $user_data->user_login,
            'needs'    => __('You offered', 'pooling') . ': ' . implode(',', $needs),
            'logo'     => 'http://covid19help.eu/wp-content/uploads/2020/03/logo128.png',
        ]);
        $this->email($provider_data->user_email, $subject, $html);

        // render and send e-mail to the aid consumer
        $subject = sprintf(__('User %s withdrew the offer!', 'pooling'), $provider_data->user_login);
        $html    = $template->render([
            'header'   => $subject,
            'message'  => sprintf(__('Please see your requests page to review it. Users have the right to withdraw an offer within %s hour(s)', 'pooling'), Helpers::secs_to_hours(POOLING_ACCEPT_OFFER_WINDOW)),
            'username' => __('Username', 'pooling') . ': ' . $provider_data->user_login,
            'needs'    => __('You offered', 'pooling') . ': ' . implode(',', $needs),
            'logo'     => 'http://covid19help.eu/wp-content/uploads/2020/03/logo128.png',
        ]);
        $this->email($user_data->user_email, $subject, $html);
    }

    /**
     * Send an email via wp_mail()
     * @param  string/array $to
     * @param  string $subject
     * @param  string $html
     * @param  array  $bcc
     * @param  array  $cc
     * @return boolean          true if mail sent or false
     */
    public function email($to, $subject, $html, $bcc = [], $cc = [])
    {
        // send an email out to user
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
        );
        !empty($bcc) && array_push($headers, 'Bcc:' . implode(',', $bcc));
        !empty($cc) && array_push($headers, 'Cc:' . implode(',', $cc));
        return wp_mail($to, $subject, $html, $headers);
    }

}
