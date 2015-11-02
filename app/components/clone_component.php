
<?php
use webtools\libs\Helper;

class CloneComponent {
	private $mode;
	private $specificFiles;

	use CloneFileMain;
	use Mode;
	use RunCopy;
	
	//main
	function runClone( $source, $destination, $specificFiles, $mode ) {
		$this->mode = $mode;
		$this->specificFiles = $specificFiles;
		Helper::make_dir( $destination );
		$this->cloneFiles( $source, $destination );
	}
}

trait CloneFileMain {
	function cloneFiles( $source, $destination ) { //recursive
		$this->checkAndSetPermissionDestination( $destination );
		$dirHandle = @opendir( $source ) or die( "Unable to open" );
		while ( $file = readdir( $dirHandle ) ) {
			$this->selectCopyMode( $source, $destination, $file ); //Mode Trait
		}
		closedir( $dirHandle );
	}

	function checkAndSetPermissionDestination( $destination ) {
		if ( ! is_dir( $destination ) ) {
			$oldumask = umask( 0 );
			mkdir( $destination, 01777 );
			umask( $oldumask );
		}
	}
}


trait Mode {
	function selectCopyMode( $source, $destination, $file ) {
		if ( 'includeMode' == $this->mode )
			$this->copyIncludeFiles( $source, $destination, $file );
		elseif ( 'excludeMode' == $this->mode )
			$this->copyExcludeFiles( $source, $destination, $file );
		elseif ( 'fullMode' == $this->mode )
			$this->copyAllFiles( $source, $destination, $file );
	}

	function copyAllFiles( $source, $destination, $file ) {
		$this->runCopy( $source, $destination, $file ); //RunCopy Trait
	}
	
	function copyExcludeFiles( $source, $destination, $file ) {
		if ( ! in_array( $source . '/' . $file, $this->specificFiles ) )
			$this->runCopy( $source, $destination, $file ); //RunCopy Trait
	}
	
	function copyIncludeFiles( $source, $destination, $file ) {
		if ( in_array( $source . '/' . $file, $this->specificFiles ) )
			$this->runCopy( $source, $destination, $file ); //RunCopy Trait
	}
}

trait RunCopy {
	function runCopy( $source, $destination, $file ) {
		$this->copyFile( $source, $destination, $file );
		$this->callRcursive( $source, $destination, $file );
	}
	
	function copyFile( $source, $destination, $file ) {
		if ( $file != "." && $file != ".." && ! is_dir( "$source/$file" ) ) { //if it is file
			$this->printCopiedFiles( $destination, $file );
			copy( "$source/$file", "$destination/$file" );
		}
	}
	
	function callRcursive( $source, $destination, $file ) {
		if( $file != "." && $file != ".." && is_dir( "$source/$file" ) ) //if it is folder
			$this->cloneFiles( "$source/$file", "$destination/$file" );
	}

	function printCopiedFiles( $destination, $file ) {
		echo $destination . '/' . $file;
		echo "\n";
	}
}