<?php
class TextDatabaseComponent {
	
	function checkExistTextFilePath( $path ) {
		try {
			if ( ! file_exists( $path ) ) throw new CustomException( 'TextFile path does not exist.' );
		} catch( CustomException $e ) {
			$e->handle();
		}
	}
	
	function getContentFromSerializeTextFile( $textFilePath ) {
		$this->checkExistTextFilePath( $textFilePath );
		$contents  = file_get_contents( $textFilePath );
        return unserialize( $contents );
	}

	// function getContentFromNormalTextFile( $path ) {
	// 	$this->checkExistTextFilePath( $path );
	// 	$files = file( $path );
	// 	return array_map( 'trim', $files );
	// }
	
	function getRandomTextFilePath( $files ) {
		shuffle( $files );
		return $files[0];
	}
	
	function readTextFileFromDirectory( $path ) { 
		return glob( $path . "*.txt" );
	}
	
	function setProductDirPath() { 
		return CONTENT_PATH . 'products/'; 
	}
	
	function setCategoryDirPath() {
		return CONTENT_PATH . 'categories.txt';
	}
	
	function setBrandDirPath() {
		return CONTENT_PATH . 'brands.txt';
	}

	function setCategoryNameListPath() {
		return CONTENT_PATH . 'categoryList.txt';
	}
	
	function getTextFileList( $path ) {
		$this->checkExistTextFilePath( $path );
		$files = $this->readTextFileFromDirectory( $path );
		return $files;
	}
}
