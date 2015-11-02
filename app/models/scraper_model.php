<?php
use webtools\controller;
use webtools\libs\Helper;
include WT_APP_PATH . 'traits/scraper/urls_trait.php';
include WT_APP_PATH . 'traits/scraper/pageInfo_trait.php';
include WT_APP_PATH . 'traits/scraper/productItems_trait.php';
include WT_APP_PATH . 'traits/scraper/database_trait.php';
include WT_APP_PATH . 'extensions/scraper-class/_simpleHtmlDom.php';
include WT_APP_PATH . 'extensions/scraper-class/_scraper.php';

class ScraperModel extends Controller {

	use UrlData;
	use PageInfo;
	use ProductItemList;
	use ProductDatabase;

	private $params;
	private $options;
	private $item;
	private $scraper;
	private $merchantName;

	function __set( $name, $value ) {
      	$this->{$name} = $value;
 	}

   	function __get( $name ) {
      	return $this->{$name};
   	}

	function initialVariables( $params, $options ) {
		$this->params = $params;
		$this->options = $options;
		$this->LoadMerchantScraper( $this->merchantName );
		$this->scraper = new Scraper(); //app/extensions/_scraper.php
	}

	function getUrlDataFromCsvFile() { //urls_trait.php
		return $this->setUrlData(); 
	}

	function getPageInfo( $item ) { //pageInfo_trait.php
		$this->item = $item;
		return $this->setPageInfo(); 
	}

	function setInputPageNumber() { //pageInfo_trait.php
		$this->inputPageNumber(); 
	}

	function setPageNumber( $pageinfo ) { //pageInfo_trait.php
		$this->setStartPageNumber( $pageinfo );
		$this->setLastPageNumber( $pageinfo );
	}

	function definePageUrl( $pageinfo, $currentPage ) { //merchant_scraper.php
		return $this->scraper->setPageUrl( $pageinfo, $currentPage );
	}

	function scrapeProductItemsFromWebPage( $url ) { //ProductItems_trait.php
		return $this->scrapeProductItems( $url );
	}

	function insertToDatabase( $productItems, $row, $currentPage ) { //database_trait.php
		$this->insertProductItemToDatabase( $productItems, $row, $currentPage );
	}

	function setUserAgent() {
		return 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0';
	}

	function setHeader() {
		return array( "Accept-Language: en-US;q=0.6,en;q=0.4" );
	}
}
