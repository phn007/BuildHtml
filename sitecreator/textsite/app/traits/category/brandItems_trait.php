<?php
trait BrandItems {
	use GetProductFiles;
	use ProductItemList;
	use GetProudctItemsFromCurrentPage;

	private $itemNumberPerPage = CATEGORY_ITEM_NUMBER_PER_PAGE;

	function getBrandItems( $params ) {
		$brandKey = $this->getBrandKey( $params );
		$currentPageNumber = $this->getCurrentPageNumber( $params );
		$productFiles = $this->getProductFiles( $brandKey ); // GetProductFiles Trait
		$brandName = $this->getBrandName( $productFiles );

		$productItemByBrand = $this->getProductItemList( $brandName, $productFiles['items'], $currentPageNumber ); //PageNumberList Trait
		$productItemByBrand = $this->getProductItemsFromCurrentPage( $brandName, $productItemByBrand, $currentPageNumber ); // GetProudctItemsFromCurrentPage Trait
	
		return array( $brandName => $productItemByBrand );
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
	function getProductItemsFromCurrentPage( $brandName, $productItemByBrand, $currentPageNumber ) {
		$productItems = $this->splitArrayIntoChunks( $productItemByBrand );
		$productItems = $this->addPageNumberIntoChunks( $productItems );
		$this->checkExistPageNumber( $productItems, $currentPageNumber );
		return $productItems[$currentPageNumber];
	}

	function checkExistPageNumber( $productItems, $currentPageNumber ) {
		$totalPage = count( $productItems );
		if ( $currentPageNumber > $totalPage ) {
			die( "Page Not Found" );
		}
	}

	function addPageNumberIntoChunks( $productItems ) {
		$i = 1;
		foreach ( $productItems as $item ) {
			$items[$i] = $item;
			$i++;
		}
		return $items;
	}

	function splitArrayIntoChunks( $productItems ) {
		return array_chunk ( $productItems, $this->itemNumberPerPage, true );
	}
}

trait ProductItemList {
	function getProductItemList( $brandName, $productFiles, $currentPageNumber ) {
		foreach ( $productFiles as $file ) {
			$productFile = $this->getProductPath() . $file . '.txt';
			$productItems = $this->readContentFromProductFile( $productFile );

			$productFile = $this->cleanProductFile( $productFile );
			$this->filterOnlyBrandNameItems( $productFile, $brandName, $productItems );
		}
		return $this->totalItems;
	}

	function filterOnlyBrandNameItems( $productFile, $brandName, $productItems ) {
		$items = array();
		foreach ( $productItems as $productKey => $item ) {
			if ( $item['brand'] == $brandName ) {
				$item['permalink'] = $this->getPermalink( $productFile, $productKey );
				$this->totalItems[$productKey] = $item;
			}
		}
	}

	function cleanProductFile( $productFile ) {
		$arr = explode( '/', $productFile );
		return str_replace( '.txt', '', end( $arr ) );
	}

	function getProductPath() {
		return CONTENT_PATH . 'products/';
	}

	function readContentFromProductFile( $productFile ) {
		return unserialize( file_get_contents( $productFile ) );
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
