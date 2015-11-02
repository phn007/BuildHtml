<?php
class AppComponent
{
	public function component( $component )
	{
		$path = APP_PATH . 'components/' . $component . '_component.php'; 
		if ( ! file_exists( $path ) )
			die( "\n" . 'The ' . $component. ' component file not found!' . "\n\n" );
		
		require_once $path;
		$arr = explode( '/', $component );
		$classname = end( $arr );
		$component_class = $classname . 'Component';
		$obj = new $component_class();
		return $obj;
	}
}