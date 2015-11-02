<?php
use webtools\controller;

include WT_BASE_PATH . 'libs/csvReaderV2.php';
include WT_APP_PATH . 'traits/getCsvConfigData_trait.php';
include WT_APP_PATH . 'traits/setupConfigV3_trait.php';
include WT_APP_PATH . 'traits/textsite/siteInfo_trait.php';

/**
 * Create Textsite
 * -------------------
 * php textsite -c bb-prosp create all
 * php textsite -c bb-prosp -r create code
 * php textsite -c bb-prosp create textdb
*/
class TextsiteController extends Controller {
	use GetCsvConfigData;
	use SetupConfig;
	use SiteInfomation;
	
	function create( $function, $params, $options ) {
		$initConfigData = $this->initialConfigDataFromCsvFile( $options );

		foreach ( $initConfigData as $dotIniFilename => $csvData ) {
			$this->initialDotINIConfigFile( $dotIniFilename, $options );
			
			if ( 'textdb' == $function || 'all' == $function ) {
				$configData = $this->getConfigData( $csvData );
				$projectName    = $this->project;
				$merchantData   = $this->getMerchantData( $csvData );
				$siteNumber     = $this->getSiteNumber( $csvData );
				$siteDirNames   = $this->getSiteDirNames( $csvData );	

				$productModel = $this->model( 'textdb/textdbProducts' );
				$productModel->create( $projectName, $dotIniFilename, $merchantData, $siteNumber, $siteDirNames );

				$categoryModel = $this->model( 'textdb/textdbCategories' );
				$categoryModel->create( $configData );

				$categoryListModel = $this->model( 'textdb/categoryListForHomepage' );
				$categoryListModel->create( $configData );
			}

			
			$siteConfigData = $this->getSiteConfigData( $csvData, $options );
			$model = $this->model( 'textsite' );
			
			foreach ( $siteConfigData as $config ) { 
				if ( 'config' == $function || 'siteall' == $function || 'all' == $function ) {
					$config['site_desc']   = $this->getSiteDescription( $config['site_category'] ); //SiteInfomation Trait
					$config['site_author'] = $this->getSiteAuthor();
					$config['prod_route']  = $this->getProdRoute();
				}

				$model->initialTextsite( $config, $options );
				if ( 'code' == $function     	 || 'siteall' == $function || 'all' == $function ) $model->code();
				if ( 'config' == $function   	 || 'siteall' == $function || 'all' == $function ) $model->siteConfig();
				if ( 'htaccess' == $function 	 || 'siteall' == $function || 'all' == $function ) $model->htaccess();
				if ( 'sitemap' == $function  	 || 'siteall' == $function || 'all' == $function ) $model->sitemap();
				if ( 'sitemapindex' == $function || 'siteall' == $function || 'all' == $function ) $model->sitemapIndex();
				if ( 'robots' == $function   	 || 'siteall' == $function || 'all' == $function ) $model->robots();
				//if ( 'logo' == $function 		 || 'siteall' == $function || 'all' == $function ) $model->logo();
				if ( array_key_exists( 'z' , $options ) ) $model->zipFiles();
				if ( 'zip' == $function ) $model->zipFiles();
				if ( 'theme' == $function ) $model->theme();
			}
		}
	}

	/**
	 * Run Server
	 * -------------------
	 * php textsite -c bb-prosp server start
	 */
	function server( $function, $params, $options ) {
		$initConfigData = $this->initialConfigDataFromCsvFile( $options );
		foreach ( $initConfigData as $dotIniFilename => $csvData ) {
			$this->initialDotINIConfigFile( $dotIniFilename, $options );
			$config = array(
				'project' => $this->project,
				'siteDir' => $csvData[0]['site_dir'],
				'hostname'  => 'http://' . $csvData[0]['domain']
			);
			$model = $this->model( 'textsite' );
			if ( 'start' == $function ) $model->serverStart( $config );
		}
	}

	/**
	 * Show Config
	 * -------------------
	 * php textsite -c bb-prosp show config
	 */
	function show( $function, $params, $options ) {
		$initConfigData = $this->initialConfigDataFromCsvFile( $options );
		foreach ( $initConfigData as $dotIniFilename => $csvData ) {
			$this->initialDotINIConfigFile( $dotIniFilename, $options );
			if ( 'config' == $function ) {
				foreach ( $this->getSiteConfigData( $csvData, $options ) as $config ) {
					print_r( $config );
					echo "\n";
				}
			}
		}
	}

	/**
	 * Calculate Domain Number
	 * -------------------
	 * php textsite -c bb-prosp calc byproducts productNumberPerDomain
	 * php textsite -c bb-prosp calc byproducts 100000
	 * php textsite -c bb-prosp calc bydomains domainNumberToCals
	 * php textsite -c bb-prosp calc bydomains 10
	 */
	function calc( $function, $params, $options  ) {
		$initConfigData = $this->initialConfigDataFromCsvFile( $options );
		foreach ( $initConfigData as $dotIniFilename => $csvData ) {
			$this->initialDotINIConfigFile( $dotIniFilename, $options );
			$merchantData = $this->getMerchantData();
			$model = $this->model( 'textdb/calculateDomainNumber' );
			if ( 'byproducts' == $function ) $model->calcByProducts( $merchantData, $params['number'] );
			if ( 'bydomains' == $function ) $model->calcByDomains( $merchantData, $params['number'] );
		}
	}

	/**
	 * Calculate Domain Number
	 * -------------------
	 * php textsite -c bb-prosp calculate byproducts productNumberPerDomain
	 * php textsite -c bb-prosp calculate byproducts 100000
	 * php textsite -c bb-prosp calculate bydomains domainNumberToCals
	 * php textsite -c bb-prosp calculate bydomains 10
	 */
	function calculate( $function, $params, $options ) {
		$merchantData = $this->getMerchantForCalcalate( $options );
		$model = $this->model( 'textdb/calculateDomainNumber' );
		if ( 'byproducts' == $function ) $model->calcByProducts( $merchantData, $params['number'] );
		if ( 'bydomains' == $function ) $model->calcByDomains( $merchantData, $params['number'] );
	}

	/**
	 * php textsite -c rexce1 db del
	 */
	//NEED TO FIX
	function DB( $function, $params, $options ) {
		die();
		$this->initIniConfig( $options );//SetupConfig Trait
		$dbs = $this->getDatabaseNames();
		$model = $this->model( 'textsite' );
		if ( $function == 'del' ) $model->deleteDatabase( $dbs );
	}

	/**
	 * php textsite -c rexce1 separator check
	 */
	function separator( $function, $params, $options ) {
		$initConfigData = $this->initialConfigDataFromCsvFile( $options );
		foreach ( $initConfigData as $dotIniFilename => $csvData ) {
			$this->initialDotINIConfigFile( $dotIniFilename, $options );
			$merchants = $this->getMerchants();

			$model = $this->model( 'textsite' );
			if ( $function == 'check' ) $model->checkSeparator( $merchants );
		}	
	}
}//class