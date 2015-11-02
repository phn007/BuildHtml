<?php
use webtools\controller;
use webtools\libs\Helper;
include WT_APP_PATH . 'traits/textdb/separateCategory_trait.php';
include WT_APP_PATH . 'traits/textdb/mysqldb_trait.php';

class TextdbProductsNewModel extends Controller {
	use MySQLDatabase;

	function create( $projectName, $merchantData, $siteNumber, $siteDirNames ) {
		$merchantData = $this->randomMerchantdata( $merchantData );
		$this->initialMysqlDatabase(); //MySQLDatabase Trait
		$countProductData = $this->countTotalProducts( $merchantData ); //MySQLDatabase Trait
		
		$temp = new TempCategoryFiles( $projectName, $siteDirNames, $merchantData, $siteNumber, $countProductData );
		$temp->createTemporaryCategoryFileGroupBySiteDir();

		$prod = new ProductFiles();
		$prod->createFiles( $projectName, $siteDirNames );
	}

	function randomMerchantdata( $merchantData ) {
		shuffle( $merchantData );
		return $merchantData;
	}
}

class ProductFiles {
	use CreateProductTextDatabase;

	function createFiles( $projectName, $siteDirNames ) {
		foreach ( $siteDirNames as $siteDirName ) {
			$source = $this->tempFilePath( $projectName, $siteDirName );
			$destination = $this->productContentPath( $projectName, $siteDirName );
			Helper::make_dir( $destination );
			$files = $this->readFileInSourceDirectory( $source );
			$this->create( $files, $destination );
		}
	}

	function create( $files, $destination ) {
		foreach ( $files as $file ) {
			$this->createProductTextDatabase( $file, $destination ); //CreateProductTextDatabase Trait
		}
	}

	function readFileInSourceDirectory( $source ) {
		return glob( $source . '*.txt' );
	}

	function tempFilePath( $projectName, $siteDirName ) {
		return TEXTSITE_PATH . $projectName . '/temp/' . $siteDirName . '/';
	}

	function productContentPath( $projectName, $siteDirName ) {
		return TEXTSITE_PATH . $projectName . '/' . $siteDirName . '/contents/products/';
	}
}

trait CreateProductTextDatabase {
	function createProductTextDatabase( $file, $destination ) {
		$this->fileNumber = 0;
		$this->countProduct = 0;
		$this->limit = 1000;
		$products = array();
		$filename = $this->getFilenameFromPath( $file );
		$writeFilename = $filename;

		$fh = fopen( $file, 'r' );
		while( ! feof( $fh ) ) {
			$content = $this->convertLineToArray( fgets( $fh ) );
			if ( !empty( $content ) ) {
				if ( ++$this->countProduct == $this->limit ) {
					$filePath = $this->getWriteFile( $destination, $writeFilename );
					$this->writeSerializeTextFile( $filePath, $products );
					$this->printResult( $filePath );

					$products = null;
					$this->countProduct = 0;
					$writeFilename = $filename . '-' . ++$this->fileNumber;
				}

				$keywordSlug = $this->getKeywordSlug( $content['keyword'] );
				$products[$keywordSlug] = $content;
			}
		}

		//write file
		if ( !empty( $products ) ) {
			$filePath = $this->getWriteFile( $destination, $writeFilename );
			$this->writeSerializeTextFile( $filePath, $products );
			$this->printResult( $filePath );
		} 
	}

	function printResult( $filePath ) {
		echo 'Create Product Database ' . $filePath;
		echo "\n";
	}

	function writeSerializeTextFile( $file, $products ) {
		$products = serialize( $products );
		file_put_contents( $file, $products );
	}	

	function getWriteFile( $destination, $writeFilename ) {
		return $destination . $writeFilename . '.txt';
	}

	function getKeywordSlug( $keyword ) {
		return Helper::clean_string( $keyword );
	}

	function convertLineToArray( $line ) {
		if ( !empty( $line ) ) {
			$arr = explode( '|', $line );
			$data = array(
				'affiliate_url' => $arr[0],
				'image_url' => $arr[1],
				'keyword' => $arr[2],
				'description' => $arr[3],
				'category' => $arr[4],
				'price' => $arr[5],
				'merchant' => $arr[6],
				'brand' => $arr[7],
			);
			return $data;	
		}
	}

	function getFilenameFromPath( $file ) {
		$arr = explode( '/', $file );
		$last = end( $arr );
		return str_replace( '.txt', '', $last );
	}
}

class TempCategoryFiles {
	use MySQLDatabase;
	use SeparateCategoryForProsperentNetwork ;

	private $countProductNumberPerSite = 0;
	private $countSiteDir = 0;
	private $network;

	function __construct( $projectName, $siteDirNames, $merchantData, $siteNumber, $countProductData ) {
		$this->projectName = $projectName;
		$this->siteDirNames = $siteDirNames;
		$this->merchantData = $merchantData;
		$totalProducts = $countProductData['totalProducts'];
		$this->merchantProductNumber = $countProductData['merchantProductNumber'];
		$this->productNumberPerSite = $this->calculateProductNumberPerSite( $totalProducts, $siteNumber );
	}

	function createTemporaryCategoryFileGroupBySiteDir() {
		$this->initialMysqlDatabase(); //MySQLDatabase Trait
		foreach ( $this->merchantData as $merchant => $data ) {
			$this->network = $data['network'];
			$dbName = $data['db_name'];
			$productNumber = $this->merchantProductNumber[$merchant];
			$sqls = $this->createSQLString( $productNumber ); //MySQLDatabase Trait		
			$this->runThroughMysqlStrings( $dbName, $sqls );	
		}
	}

	function runThroughMysqlStrings( $dbName, $sqls ) {
		foreach ( $sqls as $sql ) {
			$queryResult = $this->getQueryResult( $dbName, $sql ); //MySQLDatabase Trait
			$this->runThroughQueryResult( $queryResult, $dbName ); //RunThroughQueryResult Trait
		}
	}

	function runThroughQueryResult( $queryResult, $dbName ) {
		$tempPath = $this->createTempDir( $this->siteDirNames[$this->countSiteDir] );

		while ( $row = mysqli_fetch_array( $queryResult, MYSQLI_ASSOC ) ) {
			if ( ++$this->countProductNumberPerSite == $this->productNumberPerSite  ) {
				$this->countProductNumberPerSite = 0;
				$tempPath = $this->createTempDir( $this->siteDirNames[$this->countSiteDir] );
				$this->countSiteDir++;
			}
			$row = $this->checkEmptyCategoryAndBrand( $row );
			$row = $this->filterCategoryName( $row );
			$catSlug = $this->getCategorySlug( $row );
			$data = $this->SelectRequiredData( $row );
			$file = $this->getFileToWrite( $tempPath, $catSlug );
			$this->writeCategoryFile( $file, $data );
			$this->printResult( $file, $dbName );
		}
	}

	function printResult( $file, $dbName ) {
		STATIC $count = 1;
		echo 'Create Temporary Files ' . $dbName . ': ' . $count . '. ' . $file . "\n";
		$count++;
	}

	function writeCategoryFile( $file, $data ) {
		file_put_contents( $file, $data, FILE_APPEND | LOCK_EX );
	}

	function getFileToWrite( $tempPath, $catSlug ) {
		return $tempPath . $catSlug . '.txt';	
	}

	function SelectRequiredData( $row ) {
		$data = array(
			$row['affiliate_url'],
			$row['image_url'],
			$row['keyword'],
			$row['description'],
			$row['category'],
			$row['price'],
			$row['merchant'],
			$row['brand']
		);
		return implode( '|', $data ) . PHP_EOL;
	}

	function createTempDir( $siteDirName ) {
		$tempPath = $this->getTempFilePath( $siteDirName );
		Helper::make_dir( $tempPath );
		return $tempPath;
	}

	function getTempFilePath( $siteDirName ) {
		return TEXTSITE_PATH . $this->projectName . '/temp/' . $siteDirName . '/';
	}

	function getCategorySlug( $row ) {
		return Helper::clean_string( $row['category'] );
	}

	function filterCategoryName( $row ) {
		if ( $this->network == 'prosperent-api' ) {
			$catName = $this->getSeparatedCategory( $row['merchant'], $row['category'] ); //SeparateCategoryForProsperentNetwork Trait
			$row['category'] = $catName;
		}
		return $row;
	}

	function checkEmptyCategoryAndBrand( $row ) {
      	if ( empty( $row['category'] ) ) $row['category'] = EMPTY_CATEGORY_NAME;
      	if ( empty( $row['brand'] ) ) $row['brand'] = $row['merchant'];
		return $row;
	}

	function calculateProductNumberPerSite( $totalProducts, $siteNumber ) {
		return ceil( $totalProducts / $siteNumber );
	}
}
