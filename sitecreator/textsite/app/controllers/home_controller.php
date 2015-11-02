<?php
class HomeController extends Controller {
	function index() {
		$this->layout = HOME_LAYOUT;
		$this->view = 'index';
		$this->currentPage = 'home-page';
		$this->homeMenuState = true;

		$model = $this->model( 'home' );
		if ( ! $model->checkProductContentExist() ) return false;
		$this->productItems = $model->homeProducts();
		$this->categoryList = $model->homeCategoryList();		
		$this->brandList = $model-> homeBrandList();
		$this->seoTags = $model->homeSeoTags();	
	}
}
