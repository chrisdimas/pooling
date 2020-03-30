<?php 
namespace PLGLib\Abstracts;

/**
 * 
 */
class ErrorObject extends \JsonSerializable
{
	private $msg = [];
	public function add($msg)
	{
		array_push($this->msg,$msg);
	}

	public function __get($prop)
	{
		return $this->{$prop};
	}

	public function jsonSerialize()
	{
		return get_object_vars($this);
	}
}
 ?>