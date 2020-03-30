<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Preview\TrustedComms\Business\Insights;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Options;
use Twilio\Serialize;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 */
class ImpressionsRateContext extends InstanceContext {
    /**
     * Initialize the ImpressionsRateContext
     *
     * @param Version $version Version that contains the resource
     * @param string $businessSid Business Sid.
     */
    public function __construct(Version $version, $businessSid) {
        parent::__construct($version);

        // Path Solution
        $this->solution = ['businessSid' => $businessSid, ];

        $this->uri = '/Businesses/' . \rawurlencode($businessSid) . '/Insights/ImpressionsRate';
    }

    /**
     * Fetch a ImpressionsRateInstance
     *
     * @param array|Options $options Optional Arguments
     * @return ImpressionsRateInstance Fetched ImpressionsRateInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(array $options = []): ImpressionsRateInstance {
        $options = new Values($options);

        $params = Values::of([
            'BrandSid' => $options['brandSid'],
            'BrandedChannelSid' => $options['brandedChannelSid'],
            'PhoneNumberSid' => $options['phoneNumberSid'],
            'Country' => $options['country'],
            'Start' => Serialize::iso8601DateTime($options['start']),
            'End' => Serialize::iso8601DateTime($options['end']),
            'Interval' => $options['interval'],
        ]);

        $payload = $this->version->fetch(
            'GET',
            $this->uri,
            $params
        );

        return new ImpressionsRateInstance($this->version, $payload, $this->solution['businessSid']);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Preview.TrustedComms.ImpressionsRateContext ' . \implode(' ', $context) . ']';
    }
}