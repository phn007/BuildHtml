<?php
trait CategoryList {
	use SelectCategoryList;

	private $showNumber = 50;
	private $typeName;

	function categoryList( $catTypeName ) {
		$path = $this->categoryFilePath( $catTypeName );
		$catItems = $this->readContentFromCategoriesFile( $path );
		return $this->selectCategoryList( $catItems, $catTypeName );
	}

	function readContentFromCategoriesFile( $path ) {
		return unserialize( file_get_contents( $path ) );	
	}

	function categoryFilePath( $catTypeName ) {
		if ( 'category' == $catTypeName ) $filename = 'categories';
		elseif ( 'brand' == $catTypeName ) $filename = 'brands';
		return CONTENT_PATH . $filename . '.txt';
	}	
}

trait SelectCategoryList {
	function selectCategoryList( $catItems, $catTypeName ) {
		$itemKeys = $this->randomAndSelectKeys( $catItems );
		return $this->getCatNameAndCatLink( $itemKeys, $catItems, $catTypeName );
	}

	function getCatNameAndCatLink( $itemKeys, $catItems, $catTypeName ) {
		foreach ( $itemKeys as $key ) {
			$catName = $this->formatCatName( $catItems[$key]['name'] );
			$catLink = $this->getCategoryLink( $catTypeName, $key );
			$categoryList[$catName] = $catLink;
		}
		return $categoryList;
	}

	function formatCatName( $key ) {
		$key = strtolower( $key );
		return ucfirst( $key );
	}

	function randomAndSelectKeys( $catItems ) {
		$keys = array_keys( $catItems );
		$number = $this->getShowNumber( $keys );
		shuffle( $keys );
		return array_splice( $keys, 0, $number );
	}

	function getShowNumber( $keys ) {
		$keyNumber = count( $keys );
		return $keyNumber < $this->showNumber ? $keyNumber : $this->showNumber;
	}
}

