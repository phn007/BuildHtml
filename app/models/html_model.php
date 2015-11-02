<?php
use webtools\controller;
use webtools\libs\Helper;

include WT_APP_PATH . 'traits/html/homepage_trait.php';
include WT_APP_PATH . 'traits/html/productpage_trait.php';
include WT_APP_PATH . 'traits/html/copyFiles_trait.php';
include WT_APP_PATH . 'traits/html/categorypage_trait.php';
include WT_APP_PATH . 'traits/html/categoriespage_trait.php';
include WT_APP_PATH . 'traits/html/staticpage_trait.php';
include WT_APP_PATH . 'traits/html/server_trait.php';

class HtmlModel extends Controller {
	use HomePage;
	use ProductPage;
	use CategoryPage;
	use CategoriesPage;
	use StaticPage;
	use Files;

	private $config;
	private $sourceDir;

	function __set( $name, $value ) {
      	$this->{$name} = $value;
 	}

   	function __get( $name ) {
      	return $this->{$name};
   	}

   	function initialVariables( $config ) {
   		$this->config = $config;
   		$this->sourceDir = TEXTSITE_PATH . $config['project'] . '/' . $config['site_dir'] . '/';
   	}
   	function files() {
		$this->copyFiles(); //copyFiles_trait.php
	}

	function homePage() {
		$this->buildHomePage();	//homepage_trait.php
	}

	function productPage() {
		$this->buildProductPage(); //productpage_trait.php
	}

	function categoryPage() {
		$this->buildCategoryPage(); //categorypage_trait.php
	}

	function brandPage() {
		$this->buildBrandPage(); //categorypage_trait.php
	}

	function categoriesPage() {
		$this->buildCategoriesPage(); //categoriespage_trait.php and some of categorypage_trait.php
	}

	function brandsPage() {
		$this->buildBrandsPage(); //categoriespage_trait.php and some of categorypage_trait.php
	}
	
	function staticPage() {
		$this->buildStaticPage(); //staticpage_trait.php
	}

	function serverStart( $siteConfigData ) {
		$this->runServer( $siteConfigData );
	}
}