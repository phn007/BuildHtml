<?php
use webtools\controller;

class HostnineController extends Controller {
	/**
	* options
	 * --row row of csv file
	 *
	 *  php hostnine accounts get accountName all
	 *  php hostnine accounts get accountName domain.com
	 *
	 * Get Options
	 * -----------
	 * -s --sort 
	 * domain
	 * username
	 * ip
	 * location
	 * rquota
	 * rbandwidth
	 * rtotal_disk
	 * rtotal_bw
	 * status
	 * -a --ascending
	 * -d --descending
	 *
	 * php hostnine accounts terminate maxcom domain.com
	 * php hostnine accounts create maxcom domain.com username password "location" package
	 *
	 * php hostnine accounts modify maxcom domain.com
	 * --quota int
	 * --bandwidth int
	 */
	function accounts( $function, $params, $options ) {
		$model = $this->model( 'hostnine/accounts' );
		if ( $function == 'get' ) $model->get( $params, $options );
		if ( $function == 'create' ) $model->create( $params, $options );
		if ( $function == 'terminate' ) $model->terminate( $params, $options );
		if ( $function == 'modify' ) $model->modify( $params, $options );
	}

	function myAccount( $function, $params, $options ) {

	}

	/**
	 * php hostnine locations get
	 */
	function locations( $function, $params, $options ) {
		$model = $this->model( 'hostnine/locations' );
		$model->get();
	}

	/**
	 * php hostnine packages get maxcom                                                                                                            
	 */
	function packages( $function, $params, $options ) {
		$model = $this->model( 'hostnine/packages' );
		$model->get( $params['account'] );
	}
}


