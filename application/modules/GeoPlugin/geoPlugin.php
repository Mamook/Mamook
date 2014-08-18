<?php
/*
This PHP class is free software: you can redistribute it and/or modify
the code under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

However, the license header, copyright and author credits
must not be modified in any form and always be displayed.

This class is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

@author geoPlugin (gp_support@geoplugin.com)
@copyright Copyright geoPlugin (gp_support@geoplugin.com)
$version 1.01


This PHP class uses the PHP Webservice of http://www.geoplugin.com/ to geolocate IP addresses

Geographical location of the IP address (visitor) and locate currency (symbol, code and exchange rate) are returned.

See http://www.geoplugin.com/webservices/php for more specific details of this free service

### Adapted to PHP5 by BigTalk Jon Ryser => http://JonRyser.com ###

*/

class geoPlugin {

	# The geoPlugin server
	private $host='http://www.geoplugin.net/php.gp?ip={IP}&base_currency={CURRENCY}';

	# The default base currency
	public $currency='USD';

	# Initiate the geoPlugin data members
	public $ip=NULL;
	public $city=NULL;
	public $regionCode=NULL;
	public $regionName=NULL;
	public $areaCode=NULL;
	public $dmaCode=NULL;
	public $countryCode=NULL;
	public $countryName=NULL;
	public $continentCode=NULL;
	public $latitute=NULL;
	public $longitude=NULL;
	public $currencyCode=NULL;
	public $currencySymbol=NULL;
	public $currencyConverter=NULL;

	public function __construct()
	{
		return;
	}

	public function locate($ip=NULL)
	{
		global $_SERVER;

		if ( is_null( $ip ) ) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$host = str_replace( '{IP}', $ip, $this->host );
		$host = str_replace( '{CURRENCY}', $this->currency, $host );

		$data = array();

		$response = $this->fetch($host);

		$data = unserialize($response);

		//set the geoPlugin vars
		$this->ip = $ip;
		$this->city = $data['geoplugin_city'];
		$this->regionCode = $data['geoplugin_regionCode'];
		$this->regionName = $data['geoplugin_regionName'];
		$this->areaCode = $data['geoplugin_areaCode'];
		$this->dmaCode = $data['geoplugin_dmaCode'];
		$this->countryCode = $data['geoplugin_countryCode'];
		$this->countryName = $data['geoplugin_countryName'];
		$this->continentCode = $data['geoplugin_continentCode'];
		$this->latitude = $data['geoplugin_latitude'];
		$this->longitude = $data['geoplugin_longitude'];
		$this->currencyCode = $data['geoplugin_currencyCode'];
		$this->currencySymbol = $data['geoplugin_currencySymbol'];
		$this->currencyConverter = $data['geoplugin_currencyConverter'];
	}

	public function convert($amount, $float=2, $symbol=true)
	{
		// Easily convert amounts to geolocated currency.
		if ( !is_numeric($this->currencyConverter) || $this->currencyConverter == 0 ) {
			trigger_error('geoPlugin class Notice: currencyConverter has no value.', E_USER_NOTICE);
			return $amount;
		}
		if ( !is_numeric($amount) ) {
			trigger_error ('geoPlugin class Warning: The amount passed to geoPlugin::convert is not numeric.', E_USER_WARNING);
			return $amount;
		}
		if ( $symbol === true ) {
			return $this->currencySymbol . round( ($amount * $this->currencyConverter), $float );
		} else {
			return round( ($amount * $this->currencyConverter), $float );
		}
	}

	public function nearby($radius=10, $limit=NULL, $format='php', $country=NULL)
	{
		if ( !is_numeric($this->latitude) || !is_numeric($this->longitude) ) {
			trigger_error ('geoPlugin class Warning: Incorrect latitude or longitude values.', E_USER_NOTICE);
			return array( array() );
		}

		$host = "http://www.geoplugin.net/extras/nearby.gp?lat=" . $this->latitude . "&long=" . $this->longitude . "&radius={$radius}";

		if ( is_numeric($limit) )
			$host .= "&limit={$limit}";

		$host .= "&format={$format}";

		if ( $country!==NULL )
			$host .= "&country={$country}";

		return unserialize( $this->fetch($host) );
	}

	private function fetch($host)
	{
		if ( function_exists('curl_init') ) {

			//use cURL to fetch data
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, 'geoPlugin PHP Class v1.0');
			$response = curl_exec($ch);
			curl_close ($ch);

		} else if ( ini_get('allow_url_fopen') ) {

			//fall back to fopen()
			$response = file_get_contents($host, 'r');

		} else {

			trigger_error ('geoPlugin class Error: Cannot retrieve data. Either compile PHP with cURL support or enable allow_url_fopen in php.ini ', E_USER_ERROR);
			return;
		}

		return $response;
	}

}