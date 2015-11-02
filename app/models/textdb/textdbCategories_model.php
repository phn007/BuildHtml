<?php
use webtools\controller;
use webtools\libs\Helper;

class TextdbCategoriesModel extends Controller {
	use ReadProductDataAndWriteToTextFile;
	private $projectName;
	
	//Main
	function create( $siteConfigData ) {
		foreach ( $siteConfigData as $config ) { 
			extract( $config );
			$productDir = $this->getProductDirectories( $project, $site_dir );
			$files = $this->getProductTextFileGroupBySiteDirName( $productDir );
			$this->readProductDataAndWriteToTextFile( $files, $project, $site_dir );
		}
	}

	function getProductDirectories( $project, $site_dir ) {
		return TEXTSITE_PATH . $project . '/' . $site_dir . '/contents/products';
	}

	function getProductTextFileGroupBySiteDirName( $productDir ) {
		return glob( $productDir . '/*.txt' );
	}
	
	function printWriteCategoryProcess( $productFilename, $catTitle, $siteDirName, $catName ) {
		echo $this->projectName . ': ';
		echo $siteDirName . ': ';
		echo $productFilename . ': ';
		echo $catTitle . ': ';
		echo $catName;
		echo "\n";
	}
}

trait ReadProductDataAndWriteToTextFile {
	private $categoryData;
	private $brandData;

	function readProductDataAndWriteToTextFile( $files, $project, $site_dir ) {;
		foreach ( $files as $path ) {
			$productFilename = $this->getFilenameFromProductFilePath( $path );
			$content = $this->readProductDataFromTextFile( $path );
			$this->filterProductFilenameForEachCategory( $productFilename, $content );
		}
		$catData = $this->categoryData;
		$brandData = $this->brandData;
		$this->clearData();

		$catData = $this->parseData( $catData );
		$brandData = $this->parseData( $brandData );

		$this->writeTextFile( $catData, $project, $site_dir, 'categories' );
		$this->writeTextFile( $brandData, $project, $site_dir, 'brands' );
	}

	function filterProductFilenameForEachCategory( $productFilename, $content  ) {
		foreach ( $content as $data ) {
			$catName = $data['category'];
			$brandName = $data['brand'];
			$this->categoryData[$catName][$productFilename] = null;
			$this->brandData[$brandName][$productFilename] = null;
		}
	}

	function clearData() {
		$this->categoryData = null;
		$this->brandData = null;
	}

	function parseData( $catData ) {
		foreach ( $catData as $catName => $productFileArray ) {
			$productFileKeys = array_keys( $productFileArray );
			$slug = Helper::clean_string( $catName );

			$data[$slug] = array(
				'name' => $catName,
				'items' => $productFileKeys
			); 
		}
		return $data;
	}

	function writeTextFile( $data, $project, $site_dir, $catDir ) {
		$path = $this->setTextFilePath( $project, $site_dir, $catDir );
		Helper::make_dir( $path );
		$filename = $path . $catDir . '.txt';
		$data = serialize( $data );
		file_put_contents( $filename, $data );
		$this->printReport( $filename );

	}

	function setTextFilePath( $project, $site_dir, $catDir ) {
		return TEXTSITE_PATH . $project . '/' . $site_dir . '/contents/';
	}

	function printReport( $filename ) {
		echo "Created " . $filename . "\n";
	}

	function getFilenameFromProductFilePath( $path ) {
		$arr = explode( '/', $path );
		return str_replace( '.txt', '', end( $arr ) );
	}

	function readProductDataFromTextFile( $path ) {
		return unserialize( file_get_contents( $path ) );
	}
}
