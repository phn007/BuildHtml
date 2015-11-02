<?php 
use webtools\controller;
use webtools\libs\Helper;

class ProspApiMerchantsModel extends Controller{
	private $api_key = 'cf462123815f81df78ca0f952cefe520';
   	private $url = 'http://api.prosperent.com/api/merchant?';

   	function getMerchants() {
   		$url = $this->setURL();
   		$data = $this->curl( $url );

   		print_r( $data );

   	}

   	function setURL() {
   		return $this->url . 'api_key=' . $this->api_key . '&filterMerchant=123Inkjets|FineJewelers.com';
   	}

   	function curl( $url ) {
   		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url,
		    CURLOPT_CONNECTTIMEOUT => 30,
		    CURLOPT_TIMEOUT => 30
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response, true);

		if (count($response['errors'])) {
		    throw new Exception(implode('; ', $response['errors']));
		}
		return $response;
   	}
}