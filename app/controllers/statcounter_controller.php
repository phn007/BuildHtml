<?php
use webtools\controller;

class StatcounterController extends Controller {
	/**
	 * --row=2
	 * --row=2-4
	 * php statcounter project add csvFilename
	 */
	function project( $function, $params, $options ) {
		$model = $this->model( 'statcounter' );
		if ( $function == 'add' ) $model->add( $params, $options );
	}
}


