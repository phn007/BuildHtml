<?php
class ErrorController extends controller {
	function index() {
		header("HTTP/1.1 404 Not Found");
		$this->currentPage = 'error-page';
		$this->layout = ERROR_LAYOUT;
		$this->view = 'index';
		$this->errorPage = true;

		$model = $this->model( 'error' );
		$this->seoTags = $model->getSeoTags();
	}
}



