<?php
trait ProspBrand {
	
	function getProspApiBrands() {
		if ( ! $this->db->checkTableExist( $this->conn, 'category' ) )
			die( "Category Table not found!!!" );

		$status = 0;
		$sql = "SELECT * FROM category WHERE status ='" . $status . "'";
      	$result = mysqli_query( $this->conn, $sql );

      	while( $row = mysqli_fetch_array( $result ) ) {
      		$params = $this->setBrandParameters( $row['merchant'], $row['category'] );
      		$params = http_build_query( $params );
         	$url = $this->url . $params;
         	$options = $this->setCurlOPtions( $url );
         	$response = $this->getDataFromCurl( $options );
         	$this->parseBrandData( $row, $response );
      	}
	}

	function parseBrandData( $row, $response ) {
		if ( isset( $response['facets']['brand'] ) ) {
            $data =  $response['facets']['brand'];
            $this->createBrandTable( $this->conn );

            foreach ( $data as $brand ) {
            	$brandData = array( 
            		'merchant' => $row['merchant'], 
            		'category' => $row['category'], 
            		'brand' => $brand['value'] 
            	);
            	$this->insertBrandTable( $this->conn, $brandData );	
            	$this->printBrandResult( $row, $brand );
            }
        }
	}

	function printBrandResult( $row, $brand ) {
		static $count = 1;
		echo $this->project . ' ' . $count++ . '. Brand: ' . $row['merchant'] . ' -> ' . $row['category'] . ' -> ' . $brand['value'];
		echo "\n";
	}

	function setBrandParameters( $merchant, $category ) {
		return array(
         	'api_key' 			=> $this->api_key,
         	'enableFacets' 		=> "brand",
         	'filterMerchant' 	=> $merchant,
         	'filterCategory' 	=> $category,
         	'page' 				=> 1,
         	'limit' 			=> 1
        );
	}
}