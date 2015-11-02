<?php 
use webtools\controller;
use webtools\libs\Helper;

include WT_APP_PATH . 'traits/prospapi/prospDatabase_trait.php';
include WT_APP_PATH . 'traits/prospapi/prospCategory_trait.php';
include WT_APP_PATH . 'traits/prospapi/prospBrand_trait.php';
include WT_APP_PATH . 'traits/prospapi/prospProduct_trait.php';

class ProspApiModel extends Controller{
	use ProspDatabase;
	use ProspCategory;
	use ProspBrand;
	use ProspProduct;

	private $api_key = 'cf462123815f81df78ca0f952cefe520';
   	private $url = 'http://api.prosperent.com/api/search?';
	private $db;
	private $conn;
	private $project;

	function __set( $name, $value ) {
      	$this->{$name} = $value;
 	}

   	function __get( $name ) {
      	return $this->{$name};
   	}

	function connectDatabase() {
		$this->db = new Database();
		$this->conn = $this->db->connectDatabase();
	}

	function clearDatabase( $dbName, $clearStatus ) {
		if ( $clearStatus )
			$this->db->deleteDatabase( $this->conn, $dbName );
	}

	function createDatabase( $dbName ) {
		if ( ! $this->db->selectDatabase( $this->conn, $dbName ) ) {
			$this->db->createDatabase( $this->conn, $dbName );
			$this->db->selectDatabase( $this->conn, $dbName );
		}
	}

	function getCategories( $merchant ) {
		if ( ! $this->db->checkTableExist( $this->conn, 'category' ) )
			$this->getProspApiCategories( $merchant );
	}

	function getBrands() {
		if ( ! $this->db->checkTableExist( $this->conn, 'brand' ) )
			$this->getProspApiBrands();
	}

	function getProducts() {
		if ( ! $this->db->checkTableExist( $this->conn, 'products' ) )
			$this->getProspApiProducts();
	}

	function setCurlOPtions( $url ) {
		return array(
			'url' => $url,
			'returnTransfer' => 1,
			'connectTimeout' => 120,
			'timeout' => 120
		);
	}

	function getDataFromCurl( $options ) {
		$curl = $this->component( 'curl' );
      	$output = $curl->getRequest( $options );
      	return json_decode( $output['content'], true );
	}
}