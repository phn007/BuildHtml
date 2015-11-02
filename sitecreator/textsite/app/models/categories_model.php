<?php
include APP_PATH . 'traits/categories/categories_trait.php';
include APP_PATH . 'traits/categories/categoriesPaginator_trait.php';
include APP_PATH . 'traits/categories/categoriesSeoTags_trait.php';
include APP_PATH . 'traits/link_trait.php';

class CategoriesModel extends AppComponent {
	use Categories;
	use CategoryLink;
	use CategoriesPaginator;
	use CategoriesSeoTags;

	private $pathType;
	private $urlType;

	function getCategoryList( $params ) {
		$this->pathType = 'categories';
		$this->urlType = 'category';
		return $this->categories( $params );
	}

	function getBrandList( $params ) {
		$this->pathType = 'brands';
		$this->urlType = 'brand';
		return $this->categories( $params );
	}

	function getPagination( $params ) {
		return $this->setPagination( $params, $this->lastPage );
	}

	function getSeoTags( $menu, $category, $catType, $params ) {
		$catName = key( $category );
		$menuUrl = $menu['url'];
		return $this->getCategoriesSeoTags( $menuUrl, $catName, $catType, $params );
	}
}