<?php
include WT_BASE_PATH . 'libs/zip.php';

trait ZipFiles {
	function runZipFiles() {
		$source = $this->getZipSourcePath();
		$destination = $this->getZipDestinationPath();
		chdir( $source );
		zipData( '.', $destination );
	}

	function getZipSourcePath() {
		return TEXTSITE_PATH . $this->config['project'] . '/' . $this->config['site_dir'];
	}

	function getZipDestinationPath() {
		return TEXTSITE_PATH . $this->config['project'] . '/' . $this->config['site_dir'] . '.zip';
	}
}