<?php
use webtools\libs\Helper;

trait Htaccess {

	function createHtaccess() {
		$projectName = $this->config['project'];
		$siteDirName = $this->config['site_dir'];
		// $webType = $this->config['web_type'];
		// $webUser = $this->config['web_user'];
		$webType = '';
		$webUser = '';
		
		$hta = 'RewriteEngine On' . PHP_EOL;
		$hta .= $this->rewriteBase( $webType, $webUser ) . PHP_EOL;
		$hta .= 'RewriteCond %{REQUEST_FILENAME} !-f' . PHP_EOL;
		$hta .= 'RewriteCond %{REQUEST_FILENAME} !-d' . PHP_EOL;
		$hta .= 'RewriteRule (.*) index.php/$1' . PHP_EOL;

		$destination = $this->setHtaccessDestination( $projectName, $siteDirName );
		Helper::make_dir( $destination );
		$this->writeHtaccessFile( $destination, $hta );
		$this->printHtaccessResult( $destination );
	}
	
	function writeHtaccessFile( $destination, $content ) {
		$fh = fopen( $destination . '/.htaccess', 'w' );
		fwrite( $fh, $content );
		fclose( $fh );
	}
	
	function rewriteBase( $webType, $webUser ) {
		$hta = null;
		if ( 'temporary' == $webType ) {
			if ( empty( $webUser ) )
				die( "Cannot empty web_user variable! ( config file )\n" );
			$hta = 'RewriteBase /' . $webUser;
		}
		return $hta;
	}
	
	function setHtaccessDestination( $projectName, $siteDirName ) {
		return TEXTSITE_PATH . $projectName . '/' . $siteDirName;
	}

	function printHtaccessResult( $destination ) {
		echo "Create Htaccess file: ";
		echo $destination . '/.htaccess';
		echo " done...\n";
	}
}