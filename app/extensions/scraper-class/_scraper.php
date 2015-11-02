<?php
/**
* MainClass
*/
class Scraper {
	public $merchantUrl;

	function __construct() {
		$this->merchantUrl = setMerchantUrl();
	}

	function getPageInfo( $content ) {
		$html = $this->getHtml( $content );
		$urlFormat = $this->parseUrlFormat( $html );
		$lastPage = $this->parseLastPageNumber( $html );
		$html->clear();
        unset( $html );

        return array(
        	'urlFormat' => $urlFormat,
   			'lastPage'  => $lastPage
        );
	}

	private function parseUrlFormat( $html ) {
		$urlformat = new UrlFormat();
		return $urlformat->get( $html );
	}

	private function parseLastPageNumber( $html ) {
		$lastPageNumber = new LastpageNumber();
		return $lastPageNumber->get( $html );
	}


	function setPageUrl( $pageinfo, $currentPage ) {
		$pageUrl = new DefinePageUrl();
		return $pageUrl->set( $pageinfo, $currentPage );
	}

	function getProductItemList( $content ) {
		$html = $this->getHtml( $content );
		$productItems = $this->parseProductItems( $html );
		$html->clear();
        unset( $html );
        return $productItems;
	}

	private function getHtml( $content ) {
		$html = str_get_html( $content );
		if ( !$html ) die( "Empty Html Content: Make Log File" );
		return $html;
	}

	private function parseProductItems( $html ) {
		$productItems = new ProductItems();
		return $productItems->get( $html );
	}
}	