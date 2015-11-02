<?php
trait ResellerCentralQuery {
	function resellercentralQuery( $module, $params=null ) {
		$apiUrl = $this->getApiUrl(); //See, traits/hostnine/hostnineInfo_trait.php

		// Setup cURL options and make call to API
		$curl = curl_init();
		curl_setopt( $curl, CURLOPT_URL, $apiUrl . '?module=' . $module );

		// Check if any params need to be sent with API request
		if ( is_array( $params ) ) {
			// Prepare the params list to be posted with curl
			$paramfields = null;
			foreach( $params as $key => $value) { 
				$paramfields .= $key.'='.$value.'&'; 
			}
			rtrim( $paramfields, '&' );
			curl_setopt( $curl, CURLOPT_POST, count( $params ) );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $paramfields );
		}

		// Configure cURL request options
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 200);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		// Run cURL and check for errors
		$result = curl_exec( $curl );
		if ( curl_errno( $curl ) ) { 
			$result = Array('success' => 'false', 'result' => curl_errno($curl).' - '.curl_error($curl)); 
		}
		curl_close( $curl );

		// Decode response from API
		if ( !( $result ) ) {
			$result = Array('success' => 'false', 'result' => 'No response from the API.');
		} else {
			//$result = ((json_decode(strip_tags($result), true)) ? json_decode(strip_tags($result), true) : Array('success' => 'false', 'result' => 'Unknown response from the API. result: '.$result));
			if ( json_decode( strip_tags( $result ), true ) ) {
				$result = json_decode( strip_tags( $result ), true );
			} else {
				$result = array( 'success' => 'false', 'result' => 'Unknown response from the API. result: '.$result );
			}
		}
		return $result;
	}
}