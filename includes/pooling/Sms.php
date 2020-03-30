<?php 
namespace PLGLib;

/**
 * Twilio for sms
 */
class Sms
{
	private $number = null;
	
	function __construct()
	{
		$sid = get_option('pooling_twilio_sid',null); // Your Account SID from www.twilio.com/console
		$token = get_option('pooling_twilio_token',null); // Your Auth Token from www.twilio.com/console
		$this->number = 'COVID19HELP';
		$this->client = new \Twilio\Rest\Client($sid, $token);
	}

	public function message($to,$text)
	{
		try {
			$message = $this->client->messages->create(
			  $to, // Text this number
			  array(
			    'from' => $this->number, // From a valid Twilio number
			    'body' => $text
			  )
			);
			return $message->sid;
		} catch (\Exception $e) {
			error_log($e->getMessage() . ' code:' . $e->getCode());
		}
	}
}

?>