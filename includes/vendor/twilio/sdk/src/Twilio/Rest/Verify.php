<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest;

use Twilio\Domain;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Verify\V2;

/**
 * @property \Twilio\Rest\Verify\V2 $v2
 * @property \Twilio\Rest\Verify\V2\ServiceList $services
 * @method \Twilio\Rest\Verify\V2\ServiceContext services(string $sid)
 */
class Verify extends Domain {
    protected $_v2;

    /**
     * Construct the Verify Domain
     *
     * @param Client $client Client to communicate with Twilio
     */
    public function __construct(Client $client) {
        parent::__construct($client);

        $this->baseUrl = 'https://verify.twilio.com';
    }

    /**
     * @return V2 Version v2 of verify
     */
    protected function getV2(): V2 {
        if (!$this->_v2) {
            $this->_v2 = new V2($this);
        }
        return $this->_v2;
    }

    /**
     * Magic getter to lazy load version
     *
     * @param string $name Version to return
     * @return \Twilio\Version The requested version
     * @throws TwilioException For unknown versions
     */
    public function __get(string $name) {
        $method = 'get' . \ucfirst($name);
        if (\method_exists($this, $method)) {
            return $this->$method();
        }

        throw new TwilioException('Unknown version ' . $name);
    }

    /**
     * Magic caller to get resource contexts
     *
     * @param string $name Resource to return
     * @param array $arguments Context parameters
     * @return \Twilio\InstanceContext The requested resource context
     * @throws TwilioException For unknown resource
     */
    public function __call(string $name, array $arguments) {
        $method = 'context' . \ucfirst($name);
        if (\method_exists($this, $method)) {
            return \call_user_func_array([$this, $method], $arguments);
        }

        throw new TwilioException('Unknown context ' . $name);
    }

    protected function getServices(): \Twilio\Rest\Verify\V2\ServiceList {
        return $this->v2->services;
    }

    /**
     * @param string $sid The unique string that identifies the resource
     */
    protected function contextServices(string $sid): \Twilio\Rest\Verify\V2\ServiceContext {
        return $this->v2->services($sid);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        return '[Twilio.Verify]';
    }
}