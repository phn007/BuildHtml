<?php
include APP_PATH . 'traits/home/productItems_trait.php';
include APP_PATH . 'traits/home/categoryList_trait.php';
include APP_PATH . 'traits/link_trait.php';

class HomeModel extends AppComponent {
	use ProductItems;
	use CategoryList;
	use Permalink, CategoryLink;
	
	private $dbCom;
	private $tagCom;
	
	function __get( $name ) { 
		return $this->{$name}; 
	}
	
	function __construct() {
		$this->dbCom = $this->component( 'textdatabase' );
	}

	function homeProducts() {
		return $this->productItems(); //ProductItem Trait
	}
	
	function homeCategoryList() {
		return $this->categoryList( 'category'); //CategoryList Trait
	}
	
	function homeBrandList() {
		return $this->categoryList( 'brand' ); //CategoryList Trait
	}

	function checkProductContentExist() {
		$path = $this->dbCom->setProductDirPath();
		return file_exists( $path ) ? true : false;
	}

	function homeSeoTags() {
		$seoCom = $this->component( 'seoTags' );
		$tags = array(
			'title' => SITE_NAME,
			'description' => SITE_DESC,
			'author' => AUTHOR,
			//'robots' => 'index, follow'
		);
		return $seoCom->createSeoTags( $tags );
	}
}