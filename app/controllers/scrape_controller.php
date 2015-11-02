<?php
use webtools\controller;
/**
* Scrape Web Content
* Example commandline
* -------------------
* php scrape -c csvfile.csv get merchant-name row page
* php scrape -c csvfile.csv get nordstrom 0 0 // all rows and all pages
* php screae -c urls.csv get nordstrom 1-10 0
*/
class ScrapeController extends Controller {
	
	function get( $function, $params, $options ) {
		//To do list
		//check options
		//check merhant existing

		$model = $this->model( 'scraper' );
		$model->merchantName = $function;
		$model->initialVariables( $params, $options  );
		$urlData = $model->getUrlDataFromCsvFile();
		$model->setInputPageNumber();

		foreach ( $urlData as $row => $item ) {
			$pageinfo = $model->getPageInfo( $item );
			$model->setPageNumber( $pageinfo );
			$pageStart = $model->pageStart;
			$pageEnd = $model->pageEnd;
			$nextPage = true;
		    $currentPage = $pageStart;

		    do {
		    	$url = $model->definePageUrl( $pageinfo, $currentPage );
		    	$productItems = $model->scrapeProductItemsFromWebPage( $url );
		    	$model->insertToDatabase( $productItems, $row, $currentPage );

		    	$nextPage = $currentPage >= $pageEnd ? false : true;
               	$currentPage++;
		    } while( $nextPage );
		}
	}

	function del( $function, $params, $options ) {
		$model = $this->model( 'scraper' );
		$model->merchantName = $params['merchant'];
		if ( 'db' == $function )
			$model->deleteDatabase();
	}

	/**
	 * Example commandlne
	 * php scrape getdetail nordstrom
	 */
	function getDetail( $function, $params, $options ) {
		$model = $this->model( 'scrapeDetail' );
		$model->getProductDetail( $function );
	}
}
