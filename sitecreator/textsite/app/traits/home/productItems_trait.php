<?php
trait ProductItems {
	use GetProductPath;
	use GetProductItems;
	use DefineProductGroup;

	function productItems() {
		$cachePath = 'cache/home-products';
		$cacheName = 'home-product-file';
		$cacheTime = 300;
		$c = new Cache();
		$cache = $c->get( $cachePath, $cacheName, $cacheTime );

		if ( $cache == NULL ) {
			$pathList = $this->GetProductPathFromCategoryListForHomepage(); //GetProductPath Trait
			$productIems = $this->getProductItems( $pathList );
			$productGroups = $this->defineProductGroup( $productIems );
			$cache = $productGroups;
			$c->set( $cachePath, $cacheName, $cache );
		}
		return $cache;
	}
}

trait GetProductPath {
	function GetProductPathFromCategoryListForHomepage() {
		$path = $this->getCategoryPath();
		$this->checkFileExist( $path );
		$paths = $this->readContentFromCategoryFile( $path );
		$paths = $this->randomPaths( $paths );
		return $this->selectProductPath( $paths );
	}

	function selectProductPath( $paths ) {
		$i = 0;
		foreach ( $paths as $path ) {
			if ( ++$i > 20 ) break;
			$pathList[] = $path;
		}
		return $pathList;
	}

	function randomPaths( $items ) {
		shuffle( $items );
		return $items;
	}

	function readContentFromCategoryFile( $path ) {
		return unserialize( file_get_contents( $path ) );
	}

	function checkFileExist( $path ) {
		if ( ! file_exists( $path ) ) die( "categoryList for homepage does not exist" );
	}

	function getCategoryPath() {
		return CONTENT_PATH . 'categoryList-for-homepage.txt';
	}
}

trait GetProductItems {
	function getProductItems( $pathList ) {
		foreach ( $pathList as $path ) {
			$productFilename = $this->getProductFilenameFromPath( $path );
			$productItems[ $productFilename ] = $this->readProductContentFromFile( $path, $productFilename );
		}
		return $productItems;
	}

	function getProductFilenameFromPath( $path ) {
		$arr = explode( '/', $path );
		return str_replace( '.txt', '', end( $arr ) );
	}

	function readProductContentFromFile( $path, $productFilename ) {
		$i = 0;
		$items = unserialize( file_get_contents( BASE_PATH . $path ) );
		foreach ( $items as $productKey => $item ) {
			if ( ++$i > 12 ) break;
			$item['permalink'] = $this->getPermalink( $productFilename, $productKey );
			$itemList[ $productKey ] = $item;
		}
		return $itemList;
	}
}

trait DefineProductGroup {
	function defineProductGroup( $productItems ) {
		$productItems = $this->shuffleAssocArray( $productItems );
		$group1 = array_splice( $productItems, 0, 1 );
		$group2 = array_splice( $productItems, 0, 1 );

		return array(
			'group-one' => $group1,
			'group-two' => $group2,
			'category-group' => $productItems,
		);
	}

	function shuffleAssocArray( $productItems ) {
		$keys = array_keys( $productItems );
		shuffle( $keys );
		$shuffleItems = array();
		foreach( $keys as $key ) {
		    $shuffleItems[$key] = $productItems[$key];
		}
		return $shuffleItems;
	}
}
