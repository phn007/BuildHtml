<?php
use webtools\controller;
use webtools\libs\Helper;

class MerchantListModel extends Controller {
	function create( $configData, $merchantData ) {
		$merchants = array_keys( $merchantData );

		foreach ( $configData as $config ) {
			extract( $config );
			$saveFilename = $this->merchantListFilePath( $project, $site_dir );
			$this->saveFile( $saveFilename, $merchants );
		}
	}

	function saveFile( $filename, $merchants ) {
		$data = serialize( $merchants );
		file_put_contents( $filename, $data );
	}

	function merchantListFilePath( $project, $site_dir ) {
		return TEXTSITE_PATH . $project . '/' . $site_dir . '/contents/merchant-list.txt';
	}
}