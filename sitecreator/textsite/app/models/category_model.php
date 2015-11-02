<?php
include APP_PATH . 'traits/category/categoryItems_trait.php';
include APP_PATH . 'traits/link_trait.php';
include APP_PATH . 'traits/category/categoryPaginator_trait.php';
include APP_PATH . 'traits/category/categorySeoTags_trait.php';

class CategoryModel extends AppComponent {
	use CategoryItems;
	use Permalink, CategoryLink;
	use CategoryPaginator;
	use CategorySeoTags;

	function categoryItems( $params ) {
		return $this->getCategoryItems( $params );
	}

	function pagination( $params ) {
		return $this->getPagination( $params, $this->pageNumberList, 'category' ); 
	}

	function getSeoTags( $menu, $category, $catType, $params ) {
		$catName = key( $category );
		$menuUrl = $menu['url'];
		return $this->getCategorySeoTags( $menuUrl, $catName, $catType, $params );
	}
}

