<?php
use webtools\controller;
use webtools\libs\Helper;

include WT_BASE_PATH . 'libs/TablePrinter.php'; 
include WT_APP_PATH . 'traits/hostnine/hostnineInfo_trait.php';
include WT_APP_PATH . 'traits/hostnine/resellerCentralQuery_trait.php';

class PackagesModel extends Controller {
	use HostnineInfo;
	use ResellerCentralQuery;

	/**
	 * Retrieve a list of the available locations within Reseller Central.
	 */
	function get( $account ) {     
		$apiKey = $this->getApiKey( $account );
		$params = array( 'api_key' => $apiKey );                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
		$packages = $this->resellercentralQuery( 'getPackages', $params );
		if ( $packages['success'] != 'true' ) die( 'No result' );

		$p = new TablePrinter( [ 'ID', 'Name', 'Quota', 'bandwidth', 'quotausage', 'bwusage', 'accounts' ] );
		foreach ( $packages['sql'] as $pack ) {
			$p->addRow( 
				$pack['pack_id'], 
				$pack['name'], 
				$pack['quota'], 
				$pack['bandwidth'], 
				$pack['quotausage'], 
				$pack['bwusage'], 
				$pack['accounts']
			);
		}
		$p->output();
	}
}



