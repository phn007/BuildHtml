<?php
use webtools\controller;
use webtools\libs\Helper;

class CategoryListForHomepageModel extends Controller {
	use SelectCategory;

	function create( $siteConfigData ) {
		foreach ( $siteConfigData as $config ) { 
			extract( $config );

			$categoryPath = $this->getCategoryPath( $project, $site_dir );
			$list = $categoryList = $this->getCategoryList( $categoryPath, $project, $site_dir ); // SelectCategory Trait
			$saveFilename = $this->categoryListFilePath( $project, $site_dir );
			$this->saveFile( $saveFilename, $list );
			$this->printReport( $saveFilename );
		}
	}

	function printReport( $filename ) {
		echo 'Created: ' . $filename . " done...\n";
	}

	function saveFile( $filename, $categoryList ) {
		$data = serialize( $categoryList );
		file_put_contents( $filename, $data );
	}


	function categoryListFilePath( $project, $site_dir ) {
		return TEXTSITE_PATH . $project . '/' . $site_dir . '/contents/categoryList-for-homepage.txt';
	}

	function getCategoryPath(  $project, $site_dir ) {
		$path = TEXTSITE_PATH . $project . '/' . $site_dir . '/contents/categories.txt';
		if ( file_exists( $path ) ) {
			return $path;
		} else {
			die( $path . ' does not exists' );
		}
	}	
}

trait SelectCategory {
	function getCategoryList( $filePath, $project, $site_dir ) {
		$list = null;
		$catItems = $this->getCategoryItems( $filePath );
		$productPath = $this->getProductDirPath( $project, $site_dir );

		$i = 0;
		foreach ( $catItems as $cat ) {
			if ( ++$i > 100 ) break;
			$productFilePath = $productPath . $cat['items'][0] . '.txt';
			$productItems = $this->getProductItems( $productFilePath );
			$productItemsNumber = $this->getProductItemNumber( $productItems );

			if ( $productItemsNumber > 20 ) {
				$list[] = $this->getCategoryFilePathBeginFromRootDir( $productFilePath );
			}
		}
		return $list;
	}

	function getCategoryFilePathBeginFromRootDir( $file ) {
		$arr = explode( '/', $file );
		$lastArr = ( count( $arr ) ) - 1;
		$productNameArr = $lastArr;
		$productDirArr = $productNameArr - 1;
		$contentDirArr = $productDirArr - 1;
		return $arr[$contentDirArr] . '/' . $arr[$productDirArr] . '/' . $arr[$productNameArr];
	}

	function getProductItemNumber( $productItems ) {
		return count( $productItems );
	}

	function getProductItems( $productFilePath ) {
		return unserialize( file_get_contents( $productFilePath ) );
	}

	function getCategoryItems( $filePath ) {
		$items = unserialize( file_get_contents( $filePath ) );
		shuffle( $items );
		return $items;
	}

	function getProductDirPath( $project, $site_dir ) {
		return TEXTSITE_PATH . $project . '/' . $site_dir . '/contents/products/';
	}

}
