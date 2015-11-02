<?php
use webtools\controller;

include WT_BASE_PATH . 'libs/csvReaderV2.php';
include WT_APP_PATH . 'traits/getCsvConfigDataV2_trait.php';
include WT_APP_PATH . 'traits/setupConfigV4_trait.php';

class TextdbController extends Controller {
	use GetCsvConfigData;
	use SetupConfig;

	/**
	 * Create TextDb
	 * php controller action function csvFilename iniFilename
	 * php textdb create all rexce group1
	 * php textdb create product rexce group1
	 * php textdb create category rexce group1
	 * php textdb create homepagecat rexce group1	 
	 */

	function create( $function, $params, $options ) {
		$csvFilename = $params['csvFilename'];
		$iniFilename = $params['iniFilename'];

		$initConfigData = $this->initialConfigDataFromCsvFile( $csvFilename, $options );
		$csvData = $initConfigData[$iniFilename];
		$this->initialDotINIConfigFile( $iniFilename, $csvFilename );

		if ( $function == 'product' || $function == 'all' ) {
			$projectName    = $this->project;
			$merchantData   = $this->getMerchantData( $csvData );
			$siteNumber     = $this->getSiteNumber( $csvData );
			$siteDirNames   = $this->getSiteDirNames( $csvData );	
			$productModel = $this->model( 'textdb/textdbProducts' );
			$productModel->create( $projectName, $iniFilename, $merchantData, $siteNumber, $siteDirNames );
		}

		if ( $function == 'category' || $function == 'homepagecat' || $function == 'all' ) {
			$configData = $this->getConfigData( $csvData );
		}

		if ( $function == 'category' | $function == 'all' ) {
			$categoryModel = $this->model( 'textdb/textdbCategories' );
			$categoryModel->create( $configData );
		}

		if ( $function == 'homepagecat' || $function == 'all' ) {
			$categoryListModel = $this->model( 'textdb/categoryListForHomepage' );
			$categoryListModel->create( $configData );
		}

		if ( $function == 'merchants' || $function == 'all' ) {
			$configData = $this->getConfigData( $csvData );
			$merchantData = $this->getMerchantData( $csvData );
			$model = $this->model( 'textdb/merchantList' );
			$model->create( $configData, $merchantData );
		}
		echo "\n";
	}

	/**
	 * php textdb separator add iniFilename
	 */
	function separator( $function, $params, $options ) {
		if ( $function == 'add' ) {
			$iniFilename = $params['iniFilename'];
			$merchants = $this->getMerchantForSeparate( $iniFilename );
			$model = $this->model('separator');
			$model->addSeparator( $merchants, $iniFilename );
		}
		echo "\n";
	}

	/**
	 * php textdb calc type iniFilename number
	 * php textdb calc byproducts test1 3000
	 * php textdb calc bydomains test1 5
	 */
	function calc( $function, $params, $options ) {
		$iniFilename = $params['iniFilename'];
		$merchantData = $this->getMerchantForCalcalate( $iniFilename );
		$model = $this->model( 'textdb/calculateDomainNumber' );
		if ( 'byproducts' == $function ) $model->calcByProducts( $merchantData, $params['number'] );
		if ( 'bydomains' == $function ) $model->calcByDomains( $merchantData, $params['number'] );
		echo "\n";
	}

	/**
	 * php textdb db del demo
	 */
	function DB( $function, $params, $options ) {
		if ( $function == 'del' ) {
			$iniFilename = $params['iniFilename'];
			$dbs = $this->getDatabaseNames( $iniFilename );
			$model = $this->model( 'textdb/textdb' );
			$model->deleteDatabase( $dbs );
		}
		echo "\n";
	}
}