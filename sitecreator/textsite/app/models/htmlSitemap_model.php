<?php
include APP_PATH . 'traits/link_trait.php';
class HtmlSitemapModel extends AppComponent {
	use CategoryList;
	use SelectCategoryList;
	use Permalink, CategoryLink;

	function sitemapCategoryList() {
		return $this->categoryList( 'category'); //CategoryList Trait
	}
	
	function sitemapBrandList() {
		return $this->categoryList( 'brand' ); //CategoryList Trait
	}

	function sitemapSeoTags() {
		$seoCom = $this->component( 'seoTags' );
		$tags = array(
			'title' => 'Sitemap',
			'description' => SITE_DESC,
			'author' => AUTHOR,
			//'robots' => 'index, follow'
		);
		return $seoCom->createSeoTags( $tags );
	}
}

trait CategoryList {
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
		$itemKeys = $this->getItemKeys( $catItems );
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

	function getItemKeys( $catItems ) {
		$keys = array_keys( $catItems );
		return $keys;
	}
}
