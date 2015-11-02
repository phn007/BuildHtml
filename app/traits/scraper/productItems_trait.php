<?php
trait ProductItemList {

	function scrapeProductItems( $url ) {
		$params = $this->setProductParams( $url );
		$curl = $this->component( 'curl' );
		$output = $curl->getRequest( $params );
		return $this->scraper->getProductItemList( $output['content'] ); //merchant_scraper.php

	}

	function setProductParams( $url ) {
		return array(
			'url' => $url,
			'returnTransfer' => 1,
			'timeout' => 60,
			'followLocation' => 1,
			'userAgent' => $this->setUserAgent(),
			'httpHeader' => $this->setHeader(),
			'referer' => $this->scraper->merchantUrl,
		);
	}
}
