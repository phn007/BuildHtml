<?php
trait ProspDatabase {

	function createCategoryTable( $conn ) {
		$sql = "CREATE TABLE IF NOT EXISTS `category`
		(
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`merchant` varchar(150) NOT NULL DEFAULT '',
			`category` varchar(255) NOT NULL DEFAULT '',
			`status` char(1) NOT NULL default '0',
			PRIMARY KEY (`id`)
		)";
		// Execute query
		if ( ! mysqli_query( $conn, $sql ) )
			echo "Error creating category table: " . mysqli_error ( $conn );
	}

	function insertCategoryTable( $conn, $data ) {
		$sql = "INSERT INTO category (
			merchant,
			category,
			status
		) VALUES (
			'" . mysqli_real_escape_string( $conn, $data['merchant'] ) . "',
			'" . mysqli_real_escape_string( $conn, $data['category'] ) . "',
			'" . $data['status'] . "'
		)";

		if ( ! mysqli_query( $conn, $sql ) ) {
			echo 'Error Inserting into category table: ' . mysqli_error( $conn ) . "\n";
			echo "\n";
		}
	}

	function createBrandTable( $conn ) {
		$sql = "CREATE TABLE IF NOT EXISTS brand (
			`id` int(11) unsigned NOT NULL auto_increment,
			`merchant` varchar(150) NOT NULL default '',
			`category` varchar(255) NOT NULL default '',
			`brand` varchar(150) NOT NULL default '',
			`status` char(1) NOT NULL default '0',
			PRIMARY KEY  (`id`)
		)";

		if ( ! mysqli_query( $conn, $sql ) )
			echo "Error creating brand table: " . mysqli_error ( $conn );
	}

	function insertBrandTable( $conn, $data ) {
		$sql = "INSERT INTO brand (
			merchant,
			category,
			brand
		) VALUES (
			'" . mysqli_real_escape_string( $conn, $data['merchant'] ) . "',
			'" . mysqli_real_escape_string( $conn, $data['category'] ) . "',
			'" . mysqli_real_escape_string( $conn, $data['brand'] ) . "'
		)";

		if ( ! mysqli_query( $conn, $sql ) ) {
			echo 'Error Inserting into brand table: ' . mysqli_error( $conn );
			echo "\n";
		}
	}

	function createProductTable( $conn ) {
		$sql = "CREATE TABLE IF NOT EXISTS products (
			`id` int(11) unsigned NOT NULL auto_increment,
			`catalogId` varchar(50) NOT NULL default '',
			`productId` varchar(50) NOT NULL default '',
			`affiliate_url` varchar(255) NOT NULL default '',
			`image_url` varchar(255) NOT NULL default '',
			`keyword` varchar(255) NOT NULL default '',
			`description` text,
			`category` varchar(255) NOT NULL default '',
			`price` varchar(10) NOT NULL default '',
			`price_sale` varchar(10) NOT NULL default '',
			`currency` varchar(5) NOT NULL default '',
			`merchant` varchar(150) NOT NULL default '',
			`brand` varchar(150) NOT NULL default '',
			`upc` varchar(20) NOT NULL default '',
			`isbn` varchar(20) NOT NULL default '',
			`sales` varchar(20) NOT NULL default '',
			`status` char(1) NOT NULL default '0',
		PRIMARY KEY  (`id`)
		)";

		if ( ! mysqli_query( $conn, $sql ) ) {
			echo 'Error creating table: ' . mysqli_error( $conn ) . "\n";
		}
	}

	function insertProductTable( $conn, $data ) {
		$sql = "INSERT INTO products (
			catalogId,
			productId,
			affiliate_url,
			image_url,
			keyword,
			description,
			category,
			price,
			price_sale,
			currency,
			merchant,
			brand,
			upc,
			isbn,
			sales
		) VALUES (
			'" . $data['catalogId'] . "',
			'" . $data['productId'] . "',
			'" . mysqli_real_escape_string( $conn, $data['affiliate_url'] ) . "',
			'" . mysqli_real_escape_string( $conn, $data['image_url'] ) . "',
			'" . mysqli_real_escape_string( $conn, $data['keyword'] ) . "',
			'" . mysqli_real_escape_string( $conn, $data['description'] ) . "',
			'" . mysqli_real_escape_string( $conn, $data['category'] ) . "',
			'" . $data['price'] . "',
			'" . $data['price_sale'] . "',
			'" . $data['currency'] . "',
			'" . mysqli_real_escape_string( $conn, $data['merchant'] ) . "',
			'" . mysqli_real_escape_string( $conn, $data['brand'] ) . "',
			'" . $data['upc'] . "',
			'" . $data['isbn'] . "',
			'" . $data['sales'] . "'
		)";

		if ( ! mysqli_query( $conn, $sql ) ) {
			echo 'Error Inserting Data: ' . mysqli_error( $conn ) . "\n";
			die();
		}
	}

	function updateStatus( $conn, $id, $table, $status ) {
		$sql = "UPDATE " . $table . " SET status='" . $status . "' WHERE id='". $id ."'";
		$result = mysqli_query( $conn, $sql ) or die ('Unable to update check row.' .mysql_error( $conn ) );
	}
}