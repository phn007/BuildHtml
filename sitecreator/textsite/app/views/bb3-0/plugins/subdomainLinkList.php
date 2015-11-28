<?php
class SubdomainLinkListAddon {
	function linkList() {
		return $this->readTextFileFromDirectory();
	}

	function readTextFileFromDirectory() {
		$path = CONTENT_PATH . 'subdomains.txt';
		if ( !file_exists( $path ) )
			return false;
		
		return file( $path );
	}
}