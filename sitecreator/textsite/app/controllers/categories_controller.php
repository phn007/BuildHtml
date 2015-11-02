<?php
class CategoriesController extends Controller {
	function categories( $params ) {
		$this->currentPage = 'categories-page';
		$this->layout = CATEGORIES_LAYOUT;
      	$this->view = 'index';
      	$model = $this->model( 'categories' );
      	$this->categories = $model->getCategoryList( $params );
      	$this->paging = $model->getPagination( $params );
      	$this->catType = 'Categories';
      	$this->seoTags = $model->getSeoTags( $this->paging, $this->categories, $this->catType, $params );
	}

	function brands( $params ) {
		$this->currentPage = 'brands-page';
		$this->layout = BRANDS_LAYOUT;
      	$this->view = 'index';
      	$model = $this->model( 'categories' );
      	$this->categories = $model->getBrandList( $params );
      	$this->paging = $model->getPagination( $params );
      	$this->catType = 'Brands';
      	$this->seoTags = $model->getSeoTags( $this->paging, $this->categories, $this->catType, $params );
	}
}