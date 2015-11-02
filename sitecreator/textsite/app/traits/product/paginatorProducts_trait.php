<?php 
trait PaginatorProducts {
	use ArrayFunctions;
	use GetFilePosition;
	use PositionOfFileList;
	use GetProductFileAndProductKey;
	use CheckItemAndFile;
	use PagingUrl;
	use PagingState;

	function setPagination() {
		$this->dbCom = $this->component( 'textdatabase' );
		$filePosition = $this->navGetFilePosition(); //GetFilePosition Trait
		$data = $this->getProductFileAndProductKey( $filePosition ); //GetProductFileAndProductKey Trait
		$urls = $this->menuUrl = $this->getPagingUrl( $data );//PagingUrl Trait;
		$pagingState = $this->setPagingState( $urls ); //PagingState Trait
		return array(
			'url' => $urls,
			'state' => $pagingState
		);
	}	
}

trait PagingState {
	function setPagingState( $urls ) {
		$pagingState = $this->initialPagingState();
		$pagingState = $this->setNextState( $urls, $pagingState );
		$pagingState = $this->setPrevState( $urls, $pagingState );
		$pagingState = $this->setFirstState( $urls, $pagingState );
		$pagingState = $this->setLastState( $urls, $pagingState );
		return $pagingState;
	}

	function setNextState( $urls, $pagingState ) {
		if ( $urls['nextUrl'] == null ) $pagingState['next'] = false;
		return $pagingState;
	}

	function setLastState( $urls, $pagingState ) {
		if ( $urls['nextUrl'] == null ) $pagingState['last'] = false;
		return $pagingState;
	}

	function setPrevState( $urls, $pagingState ) {
		if ( $urls['prevUrl'] == null ) $pagingState['prev'] = false;
		return $pagingState;
	}

	function setFirstState( $urls, $pagingState ) {
		if ( $urls['prevUrl'] == null ) $pagingState['first'] = false;
		return $pagingState;
	}

	function initialPagingState() {
		$pagingState['first'] = true;
		$pagingState['prev']  = true;
		$pagingState['next']  = true;
		$pagingState['last']  = true;
		return $pagingState;
	}
}

trait GetFilePosition {
	function navGetFilePosition() {
		$filePath = $this->navGetProductDir();
		$files = $this->navGetProductFileList( $filePath );
		natsort( $files );
		return $this->setFilePosition( $files ); //PositionOfFileList trait
	}

	function navGetProductDir() {
		return $this->dbCom->setProductDirPath();
	}

	function navGetProductFileList( $path ) {
		$this->dbCom->checkExistTextFilePath( $path );
		return $this->dbCom->readTextFileFromDirectory( $path );
	}
}

trait PositionOfFileList {
	function setFilePosition( $files ) {
		return array(
			'firstFile' => $this->getFirstPositionOfFileList( $files ),
			'lastFile' => $this->getLastPositionOfFileList( $files ),
			'currentFile' => $this->getCurrentPositionOfFileList( $files ),
			'nextFile' => $this->getNexPositionOfFileList( $files ),
			'prevFile' => $this->getPreviousPositionOfFileList( $files )
		);
	}

	function getCurrentPositionOfFileList( $files ) {
		$currentKey = $this->getCurrentKey( $files );
		return $this->getPositionOfFileList( $files, $currentKey );
	}

	function getFirstPositionOfFileList( $files ) {
		$firstKey = 0;
		return $this->getPositionOfFileList( $files, $firstKey );
	}

	function getLastPositionOfFileList( $files ) {
		$lastKey = $this->getLastKey( $files );
		return $this->getPositionOfFileList( $files, $lastKey );
	}

	function getNexPositionOfFileList( $files ) {
		$nextKey = $this->getNextKey( $files );
		return $this->getPositionOfFileList( $files, $nextKey );
	}

	function getPreviousPositionOfFileList( $files ) {
		$prevKey = $this->getPreviousKey( $files );
		return $this->getPositionOfFileList( $files, $prevKey );
	}

	function getPositionOfFileList( $files, $key ) {
		$path = $this->getArrayValueByKey( $key, $files );
		$filename = $this->getFilenameFromCategoryPath( $path );
		return array(
			'key' => $key,
			'filename' => $filename,
			'path' => $path,
		);
	}

	function getCurrentKey( $files ) {
		$currentFileName = $this->productFile; //productFile from product model
		$path = $this->navGetFilePath( $currentFileName );
		return $this->findArrayKeyByValue( $path, $files );
	}

	function getLastKey( $files ) {
		$countNumber = $this->countFileNumber( $files );
		return $countNumber - 1;
	}

	function getNextKey( $files ) {
		$currentKey = $this->getCurrentKey( $files );
		return $this->setNextArray( $files , $currentKey ); //SetArrayKey Trait
	}

	function getPreviousKey( $files ) {
		$currentKey = $this->getCurrentKey( $files );
		return $this->setPreviousArray( $files, $currentKey ); //SetArrayKey Trait
	}

	function getFilenameFromCategoryPath( $path ) {
		$arr = explode( '/', $path );
		$filename = end( $arr );
		return str_replace( '.txt', '', $filename );
	}

	function navGetFilePath( $filename ) {
		return $this->navGetProductDir() . $filename . '.txt';
	}

	function countFileNumber( $files ) {
		return count( $files );
	}
}


trait GetProductFileAndProductKey {
	function getProductFileAndProductKey( $filePosition ) {
		return array(
			'current' => $this->getCurrentFileAndKey( $filePosition ),
			'first' => $this->getFirstFileAndKey( $filePosition ),
			'last' => $this->getLastFileAndkey( $filePosition ),
			'next' => $this->getNextFileAndKey( $filePosition ),
			'prev' => $this->getPrevFileAndKey( $filePosition )
		);
	}

	function getCurrentFileAndKey( $filePosition ) {
		return array(
			'productFile' => $filePosition['currentFile']['filename'],
			'productKey' =>  $this->productKey
		);
	}

	function getFirstFileAndKey( $filePosition ) {
		$firstFilePath = $filePosition['firstFile']['path'];
		$productItems = $this->getProductItemList( $firstFilePath );
		$firstKey = $this->setFirstArray( $productItems );

		return array(
			'productFile' => $filePosition['firstFile']['filename'],
			'productKey' => $firstKey
		);
	}

	function getLastFileAndkey( $filePosition ) {
		$lastFilePath = $filePosition['lastFile']['path'];
		$productItems = $this->getProductItemList( $lastFilePath );
		$lastKey = $this->setLastArray( $productItems );

		return array(
			'productFile' => $filePosition['lastFile']['filename'],
			'productKey' => $lastKey
		);
	}

	function getNextFileAndKey( $filePosition ) {
		return $this->checkNextItemAndFile( $filePosition ); //CheckItemAndFile Trait
	}

	function getPrevFileAndKey( $filePosition ) {
		return $this->checkPrevItemAndFile( $filePosition ); //CheckItemAndFile Trait
	}

	function getCurrentProductItemList( $filePosition ) {
		$currentFilePath = $filePosition['currentFile']['path'];
		return $this->getProductItemList( $currentFilePath );
	}

	function getProductItemList( $productPath ) {
		$this->dbCom->checkExistTextFilePath( $productPath );
		return $this->dbCom->getContentFromSerializeTextFile( $productPath );
	}
	
}

trait CheckItemAndFile {
	function checkNextItemAndFile( $filePosition ) {
		$currentFileAndKey = $this->getCurrentFileAndKey( $filePosition ); //PositionOfProductItem Trait
		$currentKey = $currentFileAndKey['productKey'];
		$lastKey = $this->getLastKeyFromProductItems( $filePosition['currentFile']['path'] );

		if ( $currentKey == $lastKey ) {
			if ( $this->isLastFile( $filePosition ) ) {
				$productFile = null;
				$productKey = null;
			} else {
				$productFile = $filePosition['nextFile']['filename'];
				$productKey = $this->getFirstKeyFromProductItems( $filePosition['nextFile']['path'] );
			}	
		} else {
			$productFile = $filePosition['currentFile']['filename'];
			$productKey = $this->getNextKeyFromProductItems( $filePosition['currentFile']['path'], $currentKey );
		}

		return array(
			'productFile' => $productFile,
			'productKey' => $productKey
		);
	}

	function checkPrevItemAndFile( $filePosition ) {
		$currentFileAndKey = $this->getCurrentFileAndKey( $filePosition ); //PositionOfProductItem Trait
		$currentKey = $currentFileAndKey['productKey'];
		$firstKey = $this->getFirstKeyFromProductItems( $filePosition['currentFile']['path'] );

		if ( $currentKey == $firstKey ) {
			if ( $this->isFirstFile( $filePosition ) ) {
				$productFile = null;
				$productKey = null;
			} else {
				$productFile = $filePosition['prevFile']['filename'];
				$productKey = $this->getLastKeyFromProductItems( $filePosition['prevFile']['path'] );
			}
		} else {
			$productFile = $filePosition['currentFile']['filename'];
			$productKey = $this->getPrevKeyFromProductItems( $filePosition['currentFile']['path'], $currentKey );
		}

		return array(
			'productFile' => $productFile,
			'productKey' => $productKey
		);
	}

	function isFirstFile( $filePosition ) {
		$currentFilename = $filePosition['currentFile']['filename'];
		$firstFilename = $filePosition['firstFile']['filename'];
		if ( $currentFilename == $firstFilename ) return true;
	}

	function isLastFile( $filePosition ) {
		$currentFilename = $filePosition['currentFile']['filename'];
		$lastFilename = $filePosition['lastFile']['filename'];
		if ( $currentFilename == $lastFilename ) return true;
	}

	function getPrevKeyFromProductItems( $filePath, $currentKey ) {
		$productItems = $this->getProductItemList( $filePath );
		return $this->setPreviousArray( $productItems, $currentKey );
	}

	function getNextKeyFromProductItems( $filePath, $currentKey ) {
		$productItems = $this->getProductItemList( $filePath );
		return $this->setNextArray( $productItems, $currentKey );
	}

	function getLastKeyFromProductItems( $filePath ) {
		$productItems = $this->getProductItemList( $filePath );
		return $this->setLastArray( $productItems );
	}

	function getFirstKeyFromProductItems( $filePath ) {
		$productItems = $this->getProductItemList( $filePath );
		return $this->setFirstArray( $productItems );
	}

}

trait ArrayFunctions {
	function setFirstArray( $array ) {
		reset( $array );
		return key( $array );
	}

	function setLastArray( $array ) {
		end( $array );
		return key( $array );
	}

	function setNextArray( $array, $key ) {
		reset( $array );
		while ( key( $array ) !== $key ) {
			if ( next( $array ) === false ) throw new Exception('Invalid key');
		}
		next( $array );
		return key( $array );
	}

	function setPreviousArray( $array,$key ) {
		end( $array );
		while ( key( $array ) !== $key ) {
			if ( prev( $array ) === false ) throw new Exception('Invalid key');
		}
		prev( $array );
      	return key( $array );
	}

	function getArrayValueByKey( $key, $files ) {
		$value = null;
		if ( isset( $files[$key] ) )
			$value = $files[$key];
		return $value;
	}	

	function findArrayKeyByValue( $value, $array ) {
		return array_search( $value, $array );
	}
}


trait PagingUrl {
	function getPagingUrl( $data ) {
		return array(
			'firstUrl' => $this->setUrl( $data['first'] ),
			'lastUrl'  => $this->setUrl( $data['last'] ),
			'prevUrl'  => $this->setUrl( $data['prev'] ),
			'currentUrl'  => $this->setUrl( $data['current'] ),
			'nextUrl'  => $this->setUrl( $data['next'] )
		);
	}

	function setUrl( $data ) {
		if ( $data['productFile'] == null && $data['productKey'] == null ) return null;
		return $this->getPermalink( $data['productFile'], $data['productKey'] ); //permalink_trait.php
	}
}
