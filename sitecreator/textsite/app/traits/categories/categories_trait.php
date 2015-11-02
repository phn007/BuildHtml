<?php
trait Categories {
	use PageNumber;
	use CatNameAndUrl;
	use SplitPage;

	function categories( $params ) {
		$list = $this->getCatNameAndUrl();
		$groups = $this->splitPage( $list );
		$pageNumber = $this->getCurrentPageNumber( $params, $groups );
		return $groups[$pageNumber];
	}
}

trait PageNumber {
	private $lastpage;

	function getCurrentPageNumber( $params, $groups ) {
		$this->lastPage = $this->getLastPage( $groups );
		$inputPage = $this->inputPageNumber( $params );

		if ( $inputPage <= $this->lastPage ) {
			return $inputPage;
		} else {
			die( "Categories: Page Not Found" );
		}
	}

	function inputPageNumber( $params ) {
		return isset( $params[0] ) ? $params[0] : 1;
	}

	function getLastPage( $groups ) {
		return count( $groups );
	}
}

trait SplitPage {
	function splitPage( $list ) {
		$groups = $this->chunkArray( $list );
		return $this->addNumberPageIntoGroup( $groups );
	}

	function addNumberPageIntoGroup( $groups ) {
		$i = 1;
		foreach ( $groups as $group ) {
			$data[$i] = $group;
			$i++;
		}
		return $data;
	}

	function chunkArray( $list ) {
		return array_chunk( $list, CATEGORIES_ITEM_NUMBER_PER_PAGE, true );
	}
}

trait CatNameAndUrl {
	function getCatNameAndUrl() {
		$path = $this->getPath( $this->pathType );
		$files = $this->readContentFromFile( $path );
		return  $this->creatCatList( $files );
	}

	function creatCatList( $files ) {
		foreach ( $files as $key => $file ) {
			$catName = $this->formatCategoryName( $file['name'] );
			$url = $this->createUrl( $this->urlType, $key );
			$data[$catName] = $url;
		}
		return $data;
	}

	function formatCategoryName( $catName ) {
		return ucfirst( strtolower( $catName ) );
	}

	function createUrl( $typeName, $filename ) {
		return $this->getCategoryLink( $typeName, $filename ) . FORMAT;
	}

	function readContentFromFile( $path ) {
		return unserialize( file_get_contents( $path ) );
	}

	function getPath( $catType ) {
		return CONTENT_PATH . $catType . '.txt';
	}
}