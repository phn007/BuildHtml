<?php
use webtools\controller;
use webtools\libs\Helper;

include WT_BASE_PATH . 'libs/TablePrinter.php'; 
include WT_APP_PATH . 'traits/hostnine/hostnineInfo_trait.php';
include WT_APP_PATH . 'traits/hostnine/resellerCentralQuery_trait.php';

class LocationsModel extends Controller {
	use HostnineInfo;
	use ResellerCentralQuery;

	/**
	 * Retrieve a list of the available locations within Reseller Central.
	 */
	function get() {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
		$locations = $this->resellercentralQuery( 'getLocations' );
		if ( $locations['success'] != 'true' ) die( 'No result' );

		$p = new TablePrinter( [ 'ID', 'Location', 'Priority' ] );
		foreach ( $locations['sql'] as $loc ) {
			$p->addRow( $loc['location_id'], $loc['location'], $loc['priority'] );
		}
		$p->output();
	}
}



