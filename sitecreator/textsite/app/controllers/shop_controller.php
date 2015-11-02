<?php
class ShopController extends Controller {
	public function index( $params ) {
		$model = $this->model( 'shop' );
		$url = $model->getUrl( $params );
		header( "location: " . $url ); //Redirect to Merchant
		exit(0);
	}

}
