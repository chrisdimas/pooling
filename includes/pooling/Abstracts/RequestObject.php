<?php
namespace PLGLib\Abstracts;

/**
 * RequestObject
 */
class RequestObject
{
    public $type               = 'aid_request';
    public $user_id            = null;
    public $target_user_id     = null;
    public $needs_cover        = [];
    public $target_accepted    = false;
    public $accept_timestamp   = false;
    public $withdraw           = false;
    public $withdraw_timestamp = false;
    public $created_timestamp  = false;
}
