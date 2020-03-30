<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Verify\V2;

use Twilio\Options;
use Twilio\Values;

abstract class ServiceOptions {
    /**
     * @param int $codeLength The length of the verification code to generate
     * @param bool $lookupEnabled Whether to perform a lookup with each verification
     * @param bool $skipSmsToLandlines Whether to skip sending SMS verifications to
     *                                 landlines
     * @param bool $dtmfInputRequired Whether to ask the user to press a number
     *                                before delivering the verify code in a phone
     *                                call
     * @param string $ttsName The name of an alternative text-to-speech service to
     *                        use in phone calls
     * @param bool $psd2Enabled Whether to pass PSD2 transaction parameters when
     *                          starting a verification
     * @param bool $doNotShareWarningEnabled Whether to add a security warning at
     *                                       the end of an SMS.
     * @return CreateServiceOptions Options builder
     */
    public static function create(int $codeLength = Values::NONE, bool $lookupEnabled = Values::NONE, bool $skipSmsToLandlines = Values::NONE, bool $dtmfInputRequired = Values::NONE, string $ttsName = Values::NONE, bool $psd2Enabled = Values::NONE, bool $doNotShareWarningEnabled = Values::NONE): CreateServiceOptions {
        return new CreateServiceOptions($codeLength, $lookupEnabled, $skipSmsToLandlines, $dtmfInputRequired, $ttsName, $psd2Enabled, $doNotShareWarningEnabled);
    }

    /**
     * @param string $friendlyName A string to describe the verification service
     * @param int $codeLength The length of the verification code to generate
     * @param bool $lookupEnabled Whether to perform a lookup with each verification
     * @param bool $skipSmsToLandlines Whether to skip sending SMS verifications to
     *                                 landlines
     * @param bool $dtmfInputRequired Whether to ask the user to press a number
     *                                before delivering the verify code in a phone
     *                                call
     * @param string $ttsName The name of an alternative text-to-speech service to
     *                        use in phone calls
     * @param bool $psd2Enabled Whether to pass PSD2 transaction parameters when
     *                          starting a verification
     * @param bool $doNotShareWarningEnabled Whether to add a privacy warning at
     *                                       the end of an SMS.
     * @return UpdateServiceOptions Options builder
     */
    public static function update(string $friendlyName = Values::NONE, int $codeLength = Values::NONE, bool $lookupEnabled = Values::NONE, bool $skipSmsToLandlines = Values::NONE, bool $dtmfInputRequired = Values::NONE, string $ttsName = Values::NONE, bool $psd2Enabled = Values::NONE, bool $doNotShareWarningEnabled = Values::NONE): UpdateServiceOptions {
        return new UpdateServiceOptions($friendlyName, $codeLength, $lookupEnabled, $skipSmsToLandlines, $dtmfInputRequired, $ttsName, $psd2Enabled, $doNotShareWarningEnabled);
    }
}

class CreateServiceOptions extends Options {
    /**
     * @param int $codeLength The length of the verification code to generate
     * @param bool $lookupEnabled Whether to perform a lookup with each verification
     * @param bool $skipSmsToLandlines Whether to skip sending SMS verifications to
     *                                 landlines
     * @param bool $dtmfInputRequired Whether to ask the user to press a number
     *                                before delivering the verify code in a phone
     *                                call
     * @param string $ttsName The name of an alternative text-to-speech service to
     *                        use in phone calls
     * @param bool $psd2Enabled Whether to pass PSD2 transaction parameters when
     *                          starting a verification
     * @param bool $doNotShareWarningEnabled Whether to add a security warning at
     *                                       the end of an SMS.
     */
    public function __construct(int $codeLength = Values::NONE, bool $lookupEnabled = Values::NONE, bool $skipSmsToLandlines = Values::NONE, bool $dtmfInputRequired = Values::NONE, string $ttsName = Values::NONE, bool $psd2Enabled = Values::NONE, bool $doNotShareWarningEnabled = Values::NONE) {
        $this->options['codeLength'] = $codeLength;
        $this->options['lookupEnabled'] = $lookupEnabled;
        $this->options['skipSmsToLandlines'] = $skipSmsToLandlines;
        $this->options['dtmfInputRequired'] = $dtmfInputRequired;
        $this->options['ttsName'] = $ttsName;
        $this->options['psd2Enabled'] = $psd2Enabled;
        $this->options['doNotShareWarningEnabled'] = $doNotShareWarningEnabled;
    }

    /**
     * The length of the verification code to generate. Must be an integer value between 4 and 10, inclusive.
     *
     * @param int $codeLength The length of the verification code to generate
     * @return $this Fluent Builder
     */
    public function setCodeLength(int $codeLength): self {
        $this->options['codeLength'] = $codeLength;
        return $this;
    }

    /**
     * Whether to perform a lookup with each verification started and return info about the phone number.
     *
     * @param bool $lookupEnabled Whether to perform a lookup with each verification
     * @return $this Fluent Builder
     */
    public function setLookupEnabled(bool $lookupEnabled): self {
        $this->options['lookupEnabled'] = $lookupEnabled;
        return $this;
    }

    /**
     * Whether to skip sending SMS verifications to landlines. Requires `lookup_enabled`.
     *
     * @param bool $skipSmsToLandlines Whether to skip sending SMS verifications to
     *                                 landlines
     * @return $this Fluent Builder
     */
    public function setSkipSmsToLandlines(bool $skipSmsToLandlines): self {
        $this->options['skipSmsToLandlines'] = $skipSmsToLandlines;
        return $this;
    }

    /**
     * Whether to ask the user to press a number before delivering the verify code in a phone call.
     *
     * @param bool $dtmfInputRequired Whether to ask the user to press a number
     *                                before delivering the verify code in a phone
     *                                call
     * @return $this Fluent Builder
     */
    public function setDtmfInputRequired(bool $dtmfInputRequired): self {
        $this->options['dtmfInputRequired'] = $dtmfInputRequired;
        return $this;
    }

    /**
     * The name of an alternative text-to-speech service to use in phone calls. Applies only to TTS languages.
     *
     * @param string $ttsName The name of an alternative text-to-speech service to
     *                        use in phone calls
     * @return $this Fluent Builder
     */
    public function setTtsName(string $ttsName): self {
        $this->options['ttsName'] = $ttsName;
        return $this;
    }

    /**
     * Whether to pass PSD2 transaction parameters when starting a verification.
     *
     * @param bool $psd2Enabled Whether to pass PSD2 transaction parameters when
     *                          starting a verification
     * @return $this Fluent Builder
     */
    public function setPsd2Enabled(bool $psd2Enabled): self {
        $this->options['psd2Enabled'] = $psd2Enabled;
        return $this;
    }

    /**
     * Whether to add a security warning at the end of an SMS verification body. Disabled by default and applies only to SMS. Example SMS body: `Your AppName verification code is: 1234. Don’t share this code with anyone; our employees will never ask for the code`
     *
     * @param bool $doNotShareWarningEnabled Whether to add a security warning at
     *                                       the end of an SMS.
     * @return $this Fluent Builder
     */
    public function setDoNotShareWarningEnabled(bool $doNotShareWarningEnabled): self {
        $this->options['doNotShareWarningEnabled'] = $doNotShareWarningEnabled;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        $options = [];
        foreach ($this->options as $key => $value) {
            if ($value !== Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Verify.V2.CreateServiceOptions ' . \implode(' ', $options) . ']';
    }
}

class UpdateServiceOptions extends Options {
    /**
     * @param string $friendlyName A string to describe the verification service
     * @param int $codeLength The length of the verification code to generate
     * @param bool $lookupEnabled Whether to perform a lookup with each verification
     * @param bool $skipSmsToLandlines Whether to skip sending SMS verifications to
     *                                 landlines
     * @param bool $dtmfInputRequired Whether to ask the user to press a number
     *                                before delivering the verify code in a phone
     *                                call
     * @param string $ttsName The name of an alternative text-to-speech service to
     *                        use in phone calls
     * @param bool $psd2Enabled Whether to pass PSD2 transaction parameters when
     *                          starting a verification
     * @param bool $doNotShareWarningEnabled Whether to add a privacy warning at
     *                                       the end of an SMS.
     */
    public function __construct(string $friendlyName = Values::NONE, int $codeLength = Values::NONE, bool $lookupEnabled = Values::NONE, bool $skipSmsToLandlines = Values::NONE, bool $dtmfInputRequired = Values::NONE, string $ttsName = Values::NONE, bool $psd2Enabled = Values::NONE, bool $doNotShareWarningEnabled = Values::NONE) {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['codeLength'] = $codeLength;
        $this->options['lookupEnabled'] = $lookupEnabled;
        $this->options['skipSmsToLandlines'] = $skipSmsToLandlines;
        $this->options['dtmfInputRequired'] = $dtmfInputRequired;
        $this->options['ttsName'] = $ttsName;
        $this->options['psd2Enabled'] = $psd2Enabled;
        $this->options['doNotShareWarningEnabled'] = $doNotShareWarningEnabled;
    }

    /**
     * A descriptive string that you create to describe the verification service. It can be up to 64 characters long. **This value should not contain PII.**
     *
     * @param string $friendlyName A string to describe the verification service
     * @return $this Fluent Builder
     */
    public function setFriendlyName(string $friendlyName): self {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * The length of the verification code to generate. Must be an integer value between 4 and 10, inclusive.
     *
     * @param int $codeLength The length of the verification code to generate
     * @return $this Fluent Builder
     */
    public function setCodeLength(int $codeLength): self {
        $this->options['codeLength'] = $codeLength;
        return $this;
    }

    /**
     * Whether to perform a lookup with each verification started and return info about the phone number.
     *
     * @param bool $lookupEnabled Whether to perform a lookup with each verification
     * @return $this Fluent Builder
     */
    public function setLookupEnabled(bool $lookupEnabled): self {
        $this->options['lookupEnabled'] = $lookupEnabled;
        return $this;
    }

    /**
     * Whether to skip sending SMS verifications to landlines. Requires `lookup_enabled`.
     *
     * @param bool $skipSmsToLandlines Whether to skip sending SMS verifications to
     *                                 landlines
     * @return $this Fluent Builder
     */
    public function setSkipSmsToLandlines(bool $skipSmsToLandlines): self {
        $this->options['skipSmsToLandlines'] = $skipSmsToLandlines;
        return $this;
    }

    /**
     * Whether to ask the user to press a number before delivering the verify code in a phone call.
     *
     * @param bool $dtmfInputRequired Whether to ask the user to press a number
     *                                before delivering the verify code in a phone
     *                                call
     * @return $this Fluent Builder
     */
    public function setDtmfInputRequired(bool $dtmfInputRequired): self {
        $this->options['dtmfInputRequired'] = $dtmfInputRequired;
        return $this;
    }

    /**
     * The name of an alternative text-to-speech service to use in phone calls. Applies only to TTS languages.
     *
     * @param string $ttsName The name of an alternative text-to-speech service to
     *                        use in phone calls
     * @return $this Fluent Builder
     */
    public function setTtsName(string $ttsName): self {
        $this->options['ttsName'] = $ttsName;
        return $this;
    }

    /**
     * Whether to pass PSD2 transaction parameters when starting a verification.
     *
     * @param bool $psd2Enabled Whether to pass PSD2 transaction parameters when
     *                          starting a verification
     * @return $this Fluent Builder
     */
    public function setPsd2Enabled(bool $psd2Enabled): self {
        $this->options['psd2Enabled'] = $psd2Enabled;
        return $this;
    }

    /**
     * Whether to add a privacy warning at the end of an SMS. **Disabled by default and applies only for SMS.**
     *
     * @param bool $doNotShareWarningEnabled Whether to add a privacy warning at
     *                                       the end of an SMS.
     * @return $this Fluent Builder
     */
    public function setDoNotShareWarningEnabled(bool $doNotShareWarningEnabled): self {
        $this->options['doNotShareWarningEnabled'] = $doNotShareWarningEnabled;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        $options = [];
        foreach ($this->options as $key => $value) {
            if ($value !== Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Verify.V2.UpdateServiceOptions ' . \implode(' ', $options) . ']';
    }
}