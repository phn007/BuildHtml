<?php
use webtools\controller;
use webtools\libs\Helper;

include WT_BASE_PATH . 'libs/ftpClient.php';

class FtpModel extends Controller {
	//use HostConfig;
	use UploadSiteZip;
	use RunUnzip;

	private $root = 'public_html';

	function upload( $domain, $config ) {
		$ftp = new FTPClient();
		$this->connectAndLogin( $ftp, $domain, $config );
		$this->uploadUnzipScript( $ftp );
		$this->uploadSiteZipFormat( $ftp, $config );
		$this->displayLogMessage( $ftp, $domain );
		$result = $this->runUnzip( $config );
		$this->displayListOfFilesInDirectory( $ftp );		
	}

	function displayLogMessage( $ftp, $domain ) {
		echo "-------------------------------------\n";
		echo "Uploading Files to " . $domain . "\n";
		echo "-------------------------------------\n";
		foreach ( $ftp->getMessages() as $line ) {
			echo $line;
			echo "\n";
		}
		echo "\n";	
	}
}

trait RunUnzip {
	function runUnzip( $config ) {
        $url = $config['domain'] . '/unzip.php';
        $filename = $config['site_dir'] . '.zip';
        $openPath = './' . $filename;
        $targetPath = './';
        $postData[] = array( 'open_path' => $openPath, 'target_path' => $targetPath );
        $this->unzipCurl( $url, $postData );
	}

	function unzipCurl( $url, $postData ) {
		$postData = http_build_query( $postData );
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postData );
        $result = curl_exec( $ch) ;
        curl_close( $ch );
        return $result;
	}
}

trait UploadSiteZip {
	function connectAndLogin( $ftp, $domain, $config ) {
		$ftp->host = 'ftp.' . $domain;
		$ftp->user = $config['host_user'];
		$ftp->pass = $config['host_pass'];
		if ( !$ftp->connect(true) ) die( 'Failed to connect' );
	}

	function uploadSiteZipFormat( $ftp, $config ) {
		$filename = $config['site_dir'] . '.zip';
		$fileFrom = TEXTSITE_PATH . $config['project'] . '/' . $filename;
		$fileTo = $this->root . '/' . $filename;
		$ftp->uploadFile( $fileFrom, $fileTo );
	}

	function uploadUnzipScript( $ftp ) {
		$filename = 'unzip.php';
		$fileFrom = FILES_PATH . 'scripts/' . $filename;
		$fileTo = $fileTo = $this->root . '/' . $filename;
		$ftp->uploadFile( $fileFrom, $fileTo );
	}

	function displayListOfFilesInDirectory( $ftp ) {
		$ftp->changeDir( $this->root );
		$files = $ftp->getDirListing();

		echo "----------\n";
		echo "Files List\n";
		echo "----------\n";
		foreach ( $files as $file ) {
			echo $file;
			echo "\n";
		}
	}
}
