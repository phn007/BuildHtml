<?php
trait CategoryPage {
	use CategoryNameList;
	use PageNumber;
	use BuildPage;
	use GetProductItemsForBrands;

	function buildCategoryPage() {
		$this->includeDefineSiteConfig();
		$catList = $this->getCategoryList( 'categories' ); //CategoryNameList Trait
		foreach ( $catList as $catSlug => $data ) {
			$pageNumber = $this->getCategoryPageNumber( $data['items'] ); //PageNumber Trait
			$this->buildPage( $catSlug, $pageNumber, 'category' );
		}
	}

	function buildBrandPage() {
		$this->includeDefineSiteConfig();
		$brandList = $this->getCategoryList( 'brands' ); //CategoryNameList Trait

		foreach ( $brandList as $brandSlug => $data ) {
			$brandName = $data['name'];
			$this->getProductItems( $brandName, $brandSlug, $data['items'] ); //GetProductItemsForBrands Trait
			$pageNumber = $this->getBrandPageNumber( $this->productItemsByBrandName ); //PageNumber Trait
			$this->productItemsByBrandName = null;
			//echo "Brandname: " . $brandName . ', Pagenumber: ' . $pageNumber . ' DEFINE: ' . CATEGORY_ITEM_NUMBER_PER_PAGE . "\n";
			$this->buildPage( $brandSlug, $pageNumber, 'brand' ); //BuildPage
		}
	}

	function includeDefineSiteConfig() {
		echo $path = $this->sourceDir . 'config/define-site-config.php';
		echo "\n";
		include_once( $path );
	}
}

trait BuildPage {
	function buildPage( $catSlug, $pageNumber, $catType ) {
		$projectName = $this->config['project'];
		$siteDirName = $this->config['site_dir'];
		$controller = 'category';
    	$action = $catType;
		for( $i = 1; $i <= $pageNumber; $i++ ) {
			$command  = 'php '. WT_BASE_PATH . 'buildhtml/app.php ';
			$command .= $projectName . ' ';
			$command .= $siteDirName . ' ';
			$command .= $controller . ' ';
			$command .= $action . ' ';
			$command .= $catSlug . ' ';
			$command .= $i;

			echo shell_exec( $command );
			unset( $command );
		}
	}
}

trait PageNumber {
	function getCategoryPageNumber( $filenames ) {
		$pageNumber = 0;
		foreach ( $filenames as $file ) {
			$productItems = $this->readContentFromProductFile( $file );
			$pageNumber += $this->calculatePageNumberFromProductItems( $productItems );	
		}
		return $pageNumber;
	}

	function getBrandPageNumber( $productItems ) {
		return $this->calculatePageNumberFromProductItems( $productItems );
	}

	function calculatePageNumberFromProductItems( $productItems ) {
		$totalItemsNumber = count( $productItems );
		return ceil( $totalItemsNumber / CATEGORY_ITEM_NUMBER_PER_PAGE );
	}

	function readContentFromProductFile( $file ) {
		$path = $this->getProductFilePath();
		$file = $path . $file . '.txt';
		return $this->readContentFromFile( $file ); //CategoryNameList Trait
		
	}

	function getProductFilePath() {
		return $this->sourceDir . 'contents/products/';
	}
}

trait CategoryNameList {
	function getCategoryList( $catFilename ) {
		$path = $this->categoryFilePath( $catFilename );
		return $this->readContentFromFile( $path );
	}

	function categoryFilePath( $catFilename ) {
		return $this->sourceDir . 'contents/' . $catFilename . '.txt';
	}

	function readContentFromFile( $path ) {
		$dbComPath = $this->sourceDir . 'app/components/textdatabase';
		$dbCom = $this->textSiteCreatorComponent( $dbComPath );
		return $dbCom->getContentFromSerializeTextFile( $path );
	}
}

trait GetProductItemsForBrands {
	private $productItemsByBrandName = array(); // Return Value

	function getProductItems( $brandName, $brandSlug, $productFiles ) {
		$productPath = $this->getProductFilePath(); //PageNumber Trait
		foreach ( $productFiles as $filename ) {
			$path = $productPath . $filename . '.txt';
			$productItems = $this->readContentFromFile( $path ); //CategoryNameList Trait
			$this->getProductItemsByBrandName( $productItems, $brandName );
		}
	}

	function getProductItemsByBrandName( $productItems, $brandName ) {
		foreach ( $productItems as $productKey => $item ) {
			if ( $item['brand'] == $brandName ) {
				$this->productItemsByBrandName[$productKey] = $item;
			}
		}
	}
}