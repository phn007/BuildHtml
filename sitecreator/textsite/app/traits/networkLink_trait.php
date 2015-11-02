<?php
trait ProsperentAPI {
	function prosperentApi( $affiliateUrl, $permalink ) {
		$url = $this->replaceApiKey( $affiliateUrl );
		$referrer = $this->getReferrer( $permalink );
      	$sid = $this->getSID();
      	return $url . $referrer . $sid;
	}

	function getReferrer( $permalink ) {
		return $referer = '&referrer=' . urlencode( $permalink );
	}

	function getSID() {
		return '&sid=' . urlencode( SID );
	}

	function replaceApiKey( $affiliateUrl ) {
		$arr = explode( '/', $affiliateUrl );
      	$arr[5] = API_KEY;
      	return implode( '/', $arr );
	}
}

trait Viglink {
	function viglink( $affiliateUrl ) {
		$redirect = $this->redirectUrl();
      	$u   = $this->affUrl( $affiliateUrl );
      	$key = $this->apiKey();
      	return $redirect . $u . $key;
	}

	function apiKey() {
		return '&key=' . API_KEY;
	}
	function affUrl( $affiliateUrl ) {
		return 'u=' . urlencode( $affiliateUrl );
	}

	function redirectUrl() {
		return 'http://redirect.viglink.com?';
	}
}
