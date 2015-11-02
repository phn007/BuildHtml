<?php
trait ProspProduct {
	
	function getProspApiProducts() {
		if ( ! $this->db->checkTableExist( $this->conn, 'brand' ) )
			die( "Brand Table not found!!!" );
		$this->createProductTable( $this->conn );

		$status = 0;
		$sql = "SELECT * FROM brand WHERE status ='" . $status . "'";
      	$result = mysqli_query( $this->conn, $sql );
      	
      	while( $row = mysqli_fetch_array( $result ) ) {
      		$params = $this->setProductParameters( $row );
      		$params = http_build_query( $params );
         	$url = $this->url . $params;
         	$options = $this->setCurlOPtions( $url );
         	$response = $this->getDataFromCurl( $options );
         	$this->parseProductData( $row, $response );
      	}
	}

	function parseProductData( $row, $response ) {
		if ( isset( $response['data'] ) ) {
			$data =  $response['data'];
			if ( ! $data ) return false;
			foreach ( $data as $key => $value ) {
				$this->insertProductTable( $this->conn, $value ); //ProspDatabase trait
				$this->printProductResult( $row, $value );
			}
		}
	}

	function printProductResult( $row, $value ) {
		static $count = 1;
        echo $this->project . ' ' . $count++ . '. Product: ' . $row['merchant'] . ' -> ';
        echo $row['category'] . ' -> ' . $row['brand'] . ' -> ' .  $value['keyword'];
        echo "\n";
	}

	function setProductParameters( $row ) {
		return array(
         	'api_key' => $this->api_key,
         	'filterMerchant' => $row['merchant'],
         	'filterCategory' => $row['category'],
         	'filterBrand' => $row['brand'],
         	'page' => 1,
         	'limit' => 1000
         );
	}
}