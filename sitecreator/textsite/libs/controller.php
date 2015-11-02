<?php

class Controller extends Object
{
	public function model( $model )
	{
		$path = APP_PATH . 'models/' . $model . '_model.php';
		if ( ! file_exists( $path ) )
			die( "\n" . 'The ' . $model. ' model file not found!' . "\n\n" );
		
		require_once $path;
		$arr = explode( '/', $model );
		$className = end( $arr );
		$model_class = $className . 'Model';
		$model_class = "$model_class";
		return new $model_class();
	}
}