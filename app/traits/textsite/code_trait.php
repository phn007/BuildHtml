<?php
trait Code {	
	function getAllExceptViews() {
		$source = $this->getSourcePath() . 'textsite';
		$destination = $this->getDestinationPath();
		$excludeFiles = array( 
			$source . '/app/views' , 
			$source . '/r.php' 
		);
		$this->cloneCom->runClone( $source, $destination, $excludeFiles, 'excludeMode' );
	}

	function getSourcePath() {
		return WT_BASE_PATH . 'sitecreator/';
	}
	
	function getDestinationPath() {
		return TEXTSITE_PATH . $this->config['project'] . '/' . $this->config['site_dir'] . '/';
	}	

	function getViews() {
		$source = $this->getSourcePath() . 'textsite/app/views/' . $this->config['theme_name'];
		if ( !file_exists( $source ) ) die( "\nTheme directory not found!!!\n" );

		$destination = $this->getDestinationPath() . 'app/views/' . $this->config['theme_name'];
		$excludeFiles = $this->excludeFilesGetViews( $source );
		$this->cloneCom->runClone( $source, $destination, $excludeFiles, 'excludeMode' );
	}

	function excludeFilesGetViews( $source ) {
		return array( 
			$source . '/assets/less',
			$source . '/assets/sass',
			$source . '/assets/scss',
			$source . '/assets/.sass-cache',
			$source . '/assets/config.rb'
		);
	}

	function getRouteFileForDevelopment() {
		$source = $this->getSourcePath() . 'textsite';
		$destination = $this->getDestinationPath();
		$includeFiles = array( $source . '/r.php' );
		$this->cloneCom->runClone( $source, $destination, $includeFiles, 'includeMode' );
	}

	
}
