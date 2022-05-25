<?php

require_once("../../class2.php");
require_once("vendor/autoload.php");
use ActiveCampaign;

/**
 * Active Campaign Helper Class for e107
 */
class acHelper
{

	/**
	 * @return \ActiveCampaign
	 * @throws RequestException
	 */
	private static function connect()
	{
		static $ac;

		if(!empty($ac))
		{
			return $ac;
		}

		$acPref = e107::pref('activecampaign');
		$ac = new ActiveCampaign($acPref['api_url'], $acPref['api_key']);

		return $ac;
	}

	/**
	 * @return bool
	 * @throws RequestException
	 */
	public static function test()
	{
		$ac = self::connect();
		return (bool) $ac->credentials_test();
	}


	/**
	 * @param $save
	 * @return mixed
	 */
	public static function addContact($save)
	{

		$tp = e107::getParser();
		$ac = self::connect();

		if(!empty($save['first_name']))
		{
			$save['first_name'] = $tp->filter($save['first_name']);
		}

		if(!empty($save['last_name']))
		{
			$save['last_name'] = $tp->filter($save['last_name']);
		}

		$save['email'] = filter_var($save['email'],FILTER_SANITIZE_EMAIL);


		/** @var  $response */
		$response = $ac->api("contact/sync", $save);

		if(getperms('0') && deftrue('e_DEBUG'))
		{
			echo "Debug (visible only to you): ";
			$ac->dbg($response);
		}

		return $response;
	}

	public static function getTracking()
	{
		$ac = self::connect();
		/** @var  $response */
		$response = $ac->api('tracking/site_status');
		//var_dump($response);
	}



}