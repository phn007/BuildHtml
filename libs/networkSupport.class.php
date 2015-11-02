<?php
namespace webtools\libs;

class Network {
	/**
	 * Function Call
	 * ------------
	 * SetupConfig_trait.php/convertMerchantToDbName()
	 */
	static function setDatabaseNameForEachNetwork( $network, $dbName ) {
		if ( $network == 'viglink' )
			$dbName = 'vl_' . str_replace( '-', '_', $dbName ) . '_test';
		if ( $network == 'prosperent-api' )
      		$dbName = 'prosp_' . str_replace( '-', '_', $dbName );
      	return $dbName;
	}
}