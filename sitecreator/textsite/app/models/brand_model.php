<?php
include APP_PATH . 'traits/category/brandItems_trait.php';
include APP_PATH . 'traits/link_trait.php';
include APP_PATH . 'traits/category/brandPaginator_trait.php';
include APP_PATH . 'traits/category/categorySeoTags_trait.php';

class BrandModel extends AppComponent {
	use BrandItems;
	use Permalink, CategoryLink;
	use BrandPaginator;
	use CategorySeoTags;
	
	function brandItems( $params ) {
		return $this->getBrandItems( $params );
	}

	function pagination( $params ) {
		return $this->getBrandPagination( $params, 'brand' ); 
	}

	function getSeoTags( $menu, $category, $catType, $params ) {
		$catName = key( $category );
		$menuUrl = $menu['url'];
		return $this->getCategorySeoTags( $menuUrl, $catName, $catType, $params );
	}
}

