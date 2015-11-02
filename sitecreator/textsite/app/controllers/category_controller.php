<?php
class CategoryController extends Controller {
    function category( $params ) {
        $this->currentPage = 'category-page';
        $this->layout = CATEGORY_LAYOUT;
        $this->view = 'index';
        $model = $this->model( 'category' );
        $this->category = $model->categoryItems( $params );

        // echo "<pre>";
        // print_r( $this->category );
        // die();
        $this->paging = $model->pagination( $params );
        $this->catType = 'Category';
        $this->seoTags = $model->getSeoTags( $this->paging, $this->category, $this->catType, $params );
    }

    function brand( $params ) {;
        $this->currentPage = 'brand-page';
		    $this->layout = BRAND_LAYOUT;
      	$this->view = 'index';
      	$model = $this->model( 'brand' );
      	$this->category = $model->brandItems( $params );

        // echo "<pre>";
        // print_r( $this->category );
        // die();

        
      	$this->paging = $model->pagination( $params );
        $this->catType = 'Brand';
        $this->seoTags = $model->getSeoTags( $this->paging, $this->category, $this->catType, $params );
	}
}