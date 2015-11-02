<?php
use webtools\controller;
include WT_APP_PATH . 'traits/setupConfigV4_trait.php';

/**
 * Example commandline
 * -------------------
 * php prospapi get all iniFilename
 * php prospapi get category iniFilename
 * php prospapi get brand iniFilename
 * php prospapi get product iniFilename
 */
class ProspApiController extends Controller {
	use SetupConfig;

	function get( $function, $params, $options ) {
		$iniFilename = $params['iniFilename'];
		$merchants = $this->getMerchantForProspApi( $iniFilename );
		$model = $this->model( 'prospapi' );
		$model->connectDatabase();

		foreach ( $merchants as $merchant => $data ) {
			$dbName = $data['db_name'];
			$clearStatus = $data['clear_db'];
			$model->clearDatabase( $dbName, $clearStatus );
			$model->createDatabase( $dbName );
			$model->project = $data['project'];

			if ( 'category' == $function || 'all' == $function ) 
				$model->getCategories( $merchant );
			if ( 'brand' == $function || 'all' == $function ) 
				$model->getBrands( $merchant );
			if ( 'product' == $function || 'all' == $function ) 
				$model->getProducts();
		}
	}
}