<?php
trait ProspCategory {

	function getProspApiCategories( $merchant ) {
	    $params = $this->setCategoryParameters( $merchant );
	    $params = http_build_query( $params );
	    $url = $this->url . $params;
	    $options = $this->setCurlOPtions( $url );
	    $response = $this->getDataFromCurl( $options );
	    $this->parseCategoryData( $merchant, $response );
    }


	function parseCategoryData( $merchant, $response ) {
		if ( !isset( $response['facets']['category'] ) )
        	echo "\n CURL : Empty Categories \n";

        $data =  $response['facets']['category'];
        $this->createCategoryTable( $this->conn );

        foreach ( $data as $cat ) {
        	$catData = array(
               'merchant' => $merchant,
               'category' => $cat['value'],
               'status' => 0
            );
            $this->insertCategoryTable( $this->conn, $catData );
            $this->printCategoryResult( $merchant, $cat['value'] );
        }
	}

	function printCategoryResult( $merchant, $catname ) {
        static $count = 1;
		echo $this->project . ' ' . $count++ . '. Category: ' . $merchant . ' -> ' . $catname;
		echo "\n";
	}

	function setCategoryParameters( $merchant ) {
		return array(
         	'api_key' => $this->api_key,
         	'enableFacets' => "category",
         	'filterMerchant' => $merchant,
         	'page' => 1,
         	'limit' => 1
      	);
	}	
}