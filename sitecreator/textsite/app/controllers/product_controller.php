<?php
/**
* Product detail page
*/
class ProductController extends Controller {
	function index( $params ) {
		$this->currentPage = 'product-page';
		$this->layout = PRODUCT_LAYOUT;
		$this->view = 'index';
		
		$model = $this->model( 'product' );
		$model->defineParameter( $params );

		$model->getProductDetail();
		$this->productDetail = $model->productDetail;

		$model->getSpinContent();
		$this->spinContent = $model->spinContent;

		$model->getLastestSerach();
		$this->lastestSearch = $model->lastestSearch;

		$model->getRelatedProducts();
		$this->relatedProducts = $model->relatedProducts;

		$model->getPagination();
		$this->paging = array( 'url' => $model->pagingUrl, 'state' => $model->pagingState );

		$this->seoTags = $model->getSeoTags();
	}
}