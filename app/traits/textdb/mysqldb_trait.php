<?php
trait MySQLDatabase {

	private $db;
	private $conn;
	
	function initialMysqlDatabase() {
		$this->db = new Database();
		$this->conn = $this->db->connectDatabase();
	}
	
	function countTotalProducts( $merchantData ) {
		$total = 0;
		foreach ( $merchantData as $merchant => $data ) {;
			$num = $this->countProducts( $data['db_name'] );
			$merchantProductNumber[$merchant] = $num; 
			$total = $total + $num;	
		}
		return array( 'totalProducts' => $total, 'merchantProductNumber' => $merchantProductNumber );
	}
	
	function countProducts( $dbName ) {
		$count = null;
		if ( $this->db->selectDatabase( $this->conn, $dbName ) ) {
			$count = mysqli_query( $this->conn, "SELECT COUNT(*) AS total FROM products" );
			$count = mysqli_fetch_object( $count );
			$count = $count->total;
			echo $count . ': ' . $dbName . "\n";
		}
		else 
			echo "There is no " . $dbName . ' database' . "\n";
		return $count;
	}
	
	function createSQLString( $productNumber ) {
		//$cols = "id,catalogId,affiliate_url,image_url,keyword,description,category,price,merchant,brand";
		$num_limit  = 10000;
		if ( $productNumber > $num_limit ) {
			$round = ceil( $productNumber / $num_limit );
			$start = 0;
			for ( $i = 0; $i < $round; $i++ ) {
				$limit   = ( $start ) . ', ' . $num_limit;
				$start   = $start + $num_limit;
				// $sqls[]  = "SELECT " . $cols . " FROM products LIMIT " . $limit;
				$sqls[]  = "SELECT * FROM products LIMIT " . $limit;
			}
		} else {
			// $sqls[]  = "SELECT " . $cols . " FROM products";
			$sqls[]  = "SELECT * FROM products";
		}
		return $sqls;
	}
	
	function getQueryResult( $dbName, $sql )
	{
		if ( ! $this->db->selectDatabase( $this->conn, $dbName ) )
			die( "Cannot select " . $dbName . ' database' );

		//Query Database
		if ( ! $result = mysqli_query( $this->conn, $sql ) )
			die( "Cannot Query " . $dbName . "Database" );
		return $result;
	}
}