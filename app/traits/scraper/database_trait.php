<?php
trait ProductDatabase {

	function insertProductItemToDatabase( $productItems, $row, $page ) {
		static $count = 0;

		$db = new Database();
		$conn = $db->connectDatabase();
		$db->createDatabase( $conn, $this->databaseName() );
		$db->selectDatabase( $conn, $this->databaseName() );
		$this->createProductTable( $conn );

		foreach ( $productItems as $key => $data ) {
			if ( !isset( $data['brand'] ) ) $data['brand'] = '';
			if ( !isset( $data['description'] ) )$data['description'] = '';
			$data['url'] = $this->scraper->merchantUrl . $data['url'];
			$data['category'] = $this->item['category'];
			$data['merchant'] = $this->merchantName;

			$this->insertTable( $conn, $data );
			$count++;
			$this->printResult( $row, $page, $count, $data );
		}
		$this->logLastInsert(  $row, $page  );
	}

	function logLastInsert( $row, $page ) {
		date_default_timezone_set('Asia/Bangkok');
		$time = date( "Y-m-d H:i:s" );
		$merchant = $this->merchantName;
		$log = $time . ' ' . $merchant . ' row: ' . $row . ' page: ' . $page;
		$file = WT_BASE_PATH . 'files/scraper/' . $merchant . '/log-last-insert.txt';
		file_put_contents( $file, $log );
	}

	function printResult( $row, $page, $count, $data ) {
		echo $this->merchantName . ' ';
		echo 'category -> ' . $data['category'] . ' ';
		echo 'row -> ' . $row . ' ';
		echo 'page -> ' . $page . ' ';
		echo 'product -> ' . $count . ' ' . $data['title'];
		echo "\n";
	}

	function databaseName() {
		//return 'vl_' . $this->merchantName . '_test';
		return 'vl_' . $this->merchantName;
	}

	function deleteDatabase() {
		$db = new Database();
		$conn = $db->connectDatabase();
		$db->deleteDatabase( $conn, $this->databaseName() );
	}

	function insertTable( $conn, $data ) {
		$sql = "INSERT INTO products (
			affiliate_url,
			image_url,
			keyword,
			description,
			category,
			price,
			merchant,
			brand
		) VALUES (
			'" . mysqli_real_escape_string( $conn, $data['url'] ) . "',
			'" . mysqli_real_escape_string( $conn, $data['image'] ) . "',
			'" . mysqli_real_escape_string( $conn, $data['title'] ) . "',
			'" . mysqli_real_escape_string( $conn, $data['description'] ) . "',
			'" . mysqli_real_escape_string( $conn, $data['category'] ) . "',
			'" . $data['price'] . "',
			'" . mysqli_real_escape_string( $conn, $data['merchant'] ) . "',
			'" . mysqli_real_escape_string( $conn, $data['brand'] ) . "'
		)";

		if ( ! mysqli_query( $conn, $sql ) ) {
			echo 'Error Inserting Data: ' . mysqli_error( $conn ) . "\n";
			die();
		}
	}	

	function createProductTable( $conn ) {
		$sql = "CREATE TABLE IF NOT EXISTS products (
			`id` int(11) unsigned NOT NULL auto_increment,
			`affiliate_url` varchar(255) NOT NULL default '',
			`image_url` varchar(255) NOT NULL default '',
			`keyword` varchar(255) NOT NULL default '',
			`description` text,
			`category` varchar(255) NOT NULL default '',
			`price` varchar(10) NOT NULL default '',
			`merchant` varchar(150) NOT NULL default '',
			`brand` varchar(150) NOT NULL default '',
			`status` char(1) NOT NULL default '0',
			PRIMARY KEY  (`id`)
		)";

		if ( ! mysqli_query( $conn, $sql ) ) {
			echo 'Error creating table: ' . mysqli_error( $conn ) . "\n";
		}
	}
}