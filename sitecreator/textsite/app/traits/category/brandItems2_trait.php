<?php
trait BrandItems {
	use GetProductFiles;
	use PageNumberList;
	use GetProudctItemsFromCurrentPage;

	private $itemNumberPerPage = CATEGORY_ITEM_NUMBER_PER_PAGE;
	private $startPage = 1;
	private $pageNumberList = array();

	function getBrandItems( $params ) {
		$brandKey = $this->getBrandKey( $params );
		$currentPageNumber = $this->getCurrentPageNumber( $params );
		$productFiles = $this->getProductFiles( $brandKey ); // GetProductFiles Trait
		$brandName = $this->getBrandName( $productFiles );
		$pageNumberWithProductFile = $this->getPageNumberList( $brandName, $productFiles['items'], $currentPageNumber ); //PageNumberList Trait
		$productItems = $this->getProductItemsFromCurrentPage( $brandName, $pageNumberWithProductFile, $currentPageNumber ); // GetProudctItemsFromCurrentPage Trait
		return array( $brandName => $productItems );
	}

	function getCurrentPageNumber( $params ) {
		return isset( $params[1] ) ? $params[1] : 1;
	}

	function getBrandName( $productFile ) {
		return $productFile['name'];
	}

	function getBrandKey( $params ) {
		return $params[0];
	}
}

trait GetProudctItemsFromCurrentPage {
	function getProductItemsFromCurrentPage( $brandName, $pageNumberWithProductFile, $currentPageNumber ) {
		$firstKey = $this->getFirstKeyFromArray( $pageNumberWithProductFile );
		$productFile = $this->getProductFilename( $pageNumberWithProductFile, $firstKey );
		$productItems = $this->getProductItemsFromProductFile( $productFile );
		$productItems = $this->filterOnlyBrandNameItems( $brandName, $productItems ); // PageNumberList Trait
		$productItems = $this->splitArrayIntoChunks( $productItems );
		$productItems = $this->addPageNumberIntoChunks( $productItems ,$firstKey );
		return $this->addPermalinkIntoProductItems( $productItems[$currentPageNumber], $productFile );
	}

	function addPermalinkIntoProductItems( $productItems, $productFile ) {
		$productFile = $this->cleanProductFile( $productFile );
		foreach ( $productItems as $productKey => $item ) {
			extract( $item );
			$item['permalink'] = $this->getPermalink( $productFile, $productKey );
			$items[$productKey] = $item;
		}
		return $items;
	}

	function cleanProductFile( $productFile ) {
		$arr = explode( '/', $productFile );
		return str_replace( '.txt', '', end( $arr ) );
	}

	function addPageNumberIntoChunks( $productItems ,$firstKey ) {
		foreach ( $productItems as $item ) {
			$items[$firstKey] = $item;
			$firstKey++;
		}
		return $items;
	}

	function splitArrayIntoChunks( $productItems ) {
		return array_chunk ( $productItems, $this->itemNumberPerPage, true );
	}

	function getProductItemsFromProductFile( $productFile ) {
		return $this->readContentFromProductFile( $productFile ); //PageNumberList Trait
	}

	function getProductFilename( $pageNumberWithProductFile, $firstKey ) {
		return $pageNumberWithProductFile[$firstKey];
	}

	function getFirstKeyFromArray( $pageNumberWithProductFile ) {
		reset( $pageNumberWithProductFile );
		return key( $pageNumberWithProductFile );
	}
}

trait PageNumberList {
	function getPageNumberList( $brandName, $productFiles, $currentPageNumber ) {
		foreach ( $productFiles as $file ) {
			$productFile = $this->getProductPath() . $file . '.txt';
			$productItems = $this->readContentFromProductFile( $productFile );
			$productItems = $this->filterOnlyBrandNameItems( $brandName, $productItems );
			$pageNumber = $this->calculatePageNumberFromProductItems( $productItems );
			$this->definePageNumberForProductFile( $pageNumber, $productFile );
		}
		return $this->getCurrentProductFileAndKey( $currentPageNumber );
	}

	function filterOnlyBrandNameItems( $brandName, $productItems ) {
		$items = array();
		foreach ( $productItems as $productKey => $item ) {
			if ( $item['brand'] == $brandName ) {
				$items[$productKey] = $item;
			}
		}
		return $items;
	}

	function getCurrentProductFileAndKey( $currentPageNumber ) {
		$this->checkExistPageNumber( $currentPageNumber );
		$productFileForCurrentPage = $this->pageNumberList[$currentPageNumber];
		foreach ( $this->pageNumberList as $key => $file ) {
			if ( $file == $productFileForCurrentPage ) {
				$productFileAndKey[$key] = $file;
			}
		}
		return $productFileAndKey;
	}

	function checkExistPageNumber( $currentPageNumber ) {
		end( $this->pageNumberList );
		$lastPageNumber = key( $this->pageNumberList );
		
		if ( $currentPageNumber > $lastPageNumber )
			die( "Page Not Found\n");
	}

	function definePageNumberForProductFile( $pageNumber, $productFile ) {
		$lastPage = $this->startPage + $pageNumber;
		for ( $i = $this->startPage; $i < $lastPage; $i++ ) {
			$this->pageNumberList[$i] = $productFile;
		}
		$this->startPage = $i;
	}

	function getProductPath() {
		return CONTENT_PATH . 'products/';
	}

	function readContentFromProductFile( $productFile ) {
		return unserialize( file_get_contents( $productFile ) );
	}

	function calculatePageNumberFromProductItems( $productItems ) {
		$totalItemsNumber = count( $productItems );
		return ceil( $totalItemsNumber / $this->itemNumberPerPage );
	}
}

trait GetProductFiles {
	function getProductFiles( $brandKey ) {
		$path = $this->getCategoryPath();
		$brandItems = $this->readContentFromCategoryFile( $path );
		return $this->getProductFilenameFromBrandItem( $brandKey, $brandItems );
	}

	function getProductFilenameFromBrandItem( $brandKey, $brandItems ) {
		if ( array_key_exists( $brandKey, $brandItems ) ) 
			return $brandItems[$brandKey];
		else
			die( "Category: " . $brandKey . ' file not found' );
	}

	function readContentFromCategoryFile( $catPath ) {
		return unserialize( file_get_contents( $catPath ) );
	}

	function getCategoryPath() {
		return CONTENT_PATH . 'brands.txt';
	}
}
