<?php
trait ProductPage {

	function buildProductPage() {
		$dbComPath = $this->sourceDir . 'app/components/textdatabase';
		$dbCom = $this->textSiteCreatorComponent( $dbComPath );

		$productFiles = $this->getProductTextFiles( $dbCom );
		foreach ( $productFiles as $file ) {
			$productContents = $dbCom->getContentFromSerializeTextFile( $file );
			$productKeys = array_keys( $productContents );
			$filename = $this->getFilenameFromPath( $file );
			$this->loopThroughProductContents( $filename, $productKeys );
		}
	}

	function loopThroughProductContents( $filename, $productKeys ) {
		$controller = 'product';
    	$action = 'index';

		foreach ( $productKeys as $key ) {
			$projectName = $this->config['project'];
			$siteDirName = $this->config['site_dir'];
			
			$command = 'php '. WT_BASE_PATH . 'buildhtml/app.php ';
			$command .= $projectName . ' ';
			$command .= $siteDirName . ' ';
			$command .= $controller . ' ';
			$command .= $action . ' ';
			$command .= $filename . ' ';
			$command .= $key;
			echo shell_exec( $command );
		}
	}

	function getFilenameFromPath( $file ) {
		$arr = explode( '/', $file );
		$filename = end( $arr );
		return str_replace( '.txt', '', $filename );
	}

	function getProductTextFiles( $dbCom ) {
		$productPath = $this->setProductPath();
		$dbCom->checkExistTextFilePath( $productPath );
		return $dbCom->readTextFileFromDirectory( $productPath );
	}

	function setProductPath() {
		return $this->sourceDir . 'contents/products/';
	}
}