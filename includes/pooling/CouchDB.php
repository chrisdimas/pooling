<?php 
namespace PLGLib;
use PHPOnCouch\CouchClient;
use PHPOnCouch\CouchDocument;

/**
 * CouchDB
 */
class CouchDB
{
	const USER_PREFIX = 'pooling_user_';

	private static $instance = null;
	public static function connect()
	{
		if (self::$instance === null) {
			$client = new CouchClient(get_option('pooling_couchdb_url',null),get_option('pooling_couchdb_db',null));
			self::$instance = $client;
			return $client;
		} else {
			return self::$instance;
		}
	}

	public static function get($id)
	{
		try {
		    $doc = self::connect()->asCouchDocuments()->getDoc($id);
		    return $doc;
		} catch ( \Exception $e ) {
		    if ( $e->getCode() == 404 ) {
				return false;
			}
		}
	}

	public static function get_user_doc_id($user_id)
	{
		return ((defined('POOLING_COUCHDB_USER_PREFIX') ? POOLING_COUCHDB_USER_PREFIX : self::USER_PREFIX ) . $user_id);
	}
}
 ?>