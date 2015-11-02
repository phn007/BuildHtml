<?php
use webtools\controller;
include WT_BASE_PATH . 'libs/csvReaderV2.php';
include WT_APP_PATH . 'traits/getCsvConfigDataV2_trait.php';
include WT_APP_PATH . 'traits/setupConfigV4_trait.php';

class FtpController extends Controller {
	use GetCsvConfigData;
	use SetupConfig;

	/**
	 * php ftp upload csvFilename domain
	 */
	function upload( $function, $params, $options ) {
		$csvFilename = $function;
		$domain = $params['domain'];
		$initConfigData = $this->initialConfigDataFromCsvFileForFtp( $csvFilename, null );
		$csvData = $initConfigData[ $domain ];
		$iniFilename = $csvData['config_file'];

		$this->initialDotINIConfigFile( $iniFilename, $csvFilename );
		$config = $this->getConfigDataForFtp( $csvData );
		$config['host_user'] = $csvData['host_user'];
		$config['host_pass'] = $csvData['host_pass'];

		$model = $this->model( 'ftp' );
		$model->upload( $domain, $config );
	}
}