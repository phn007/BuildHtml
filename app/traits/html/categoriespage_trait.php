<?php
trait CategoriesPage {
	use CategoriesList;

	function buildCategoriesPage() {
		$this->includeDefineSiteConfig(); //CategoryPage Trait
		$items = $this->getCategoriesList( 'categories' );
		$pageNumber = $this->getPageNumber( $items );
		$this->runBuildPage( $pageNumber, 'categories' );
	}

	function buildBrandsPage() {
		$this->includeDefineSiteConfig(); //CategoryPage Trait
		$items = $this->getCategoriesList( 'brands' );
		$pageNumber = $this->getPageNumber( $items );
		$this->runBuildPage( $pageNumber, 'brands' );
	}

	function getPageNumber( $items ) {
		return count( array_chunk( $items, CATEGORIES_ITEM_NUMBER_PER_PAGE ) );
	}

	function runBuildPage( $pageNumber, $catType ) {
		$projectName = $this->config['project'];
		$siteDirName = $this->config['site_dir'];
		$controller = 'categories';
    	$action = $catType;
		for( $i = 1; $i <= $pageNumber; $i++ ) {
			$command  = 'php '. WT_BASE_PATH . 'buildhtml/app.php ';
			$command .= $projectName . ' ';
			$command .= $siteDirName . ' ';
			$command .= $controller . ' ';
			$command .= $action . ' ';
			$command .= $i;
			echo shell_exec( $command );
			unset( $command );
		}
	}
}


trait CategoriesList {
	function getCategoriesList( $catFilename ) {
		$path = $this->getCategoriesFilePath( $catFilename );
		$catItemList = $this->readContentFromFile( $path ); //CategoryNameList Triat
		return array_keys( $catItemList );
	}

	function getCategoriesFilePath( $catFilename ) {
		return $this->sourceDir . 'contents/' . $catFilename . '.txt';
	}
}