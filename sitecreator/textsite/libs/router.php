<?php

class Map extends Object {

	public static $path = null;

	public static function get($route, $path) {
		self::$path = $path;
		Sammy::process($route, 'GET');
	}

	public static function post($route, $path) {
		self::$path = $path;
		Sammy::process($route, 'POST');
	}

	public static function put($route, $path) {
		self::$path = $path;
		Sammy::process($route, 'PUT');
	}

	public static function delete($route, $path) {
		self::$path = $path;
		Sammy::process($route, 'DELETE');
	}

	public static function ajax($route, $path) {
		self::$path = $path;
		Sammy::process($route, 'XMLHttpRequest');
	}


	public static function pre_dispatch( $uri ) {
		/*
		 * Define Variable for product detail page
		 * ----------------------------------------------------------------------
		*/
		//Controller และ Action
		$controller = 'product';
		$action = 'index';
		self::$path = $controller . '#' . $action;

		//http://domain.com/category-slug/keyword-slug.html
		$path = explode('/', $uri);
		$product_file = $path[0];
		$product_key  = str_replace( FORMAT , '', $path[1] );
		$params = array( $product_file, $product_key );

		self::dispatch( $params );
	}

	public static function dispatch( $params = false ) {
		//run when find a matching route
		$path = explode( '#', self::$path );
		$controller = strtolower( $path[0] );
		$action = $path[1];

		//include the matching controller
		self::load_controller( $controller );

		//Run Class/Action
		$controller = str_replace( '-', '', $controller );
		$class_name = ucfirst( $controller ) . 'Controller';
		
		if ( ! class_exists( $class_name ) ) {
			$msg  = '<span style="color:red">The class <strong> ';
			$msg .=  $class_name . '</strong> could not be found in <pre>';
			$msg .= APP_PATH . 'controllers/' . $controller . '_controller.php</pre></span>';
			die( $msg );
			//header( 'location:' . HOME_URL . 'error' );
		}
		$tmp_class = new $class_name();
			
		//run the matching action
		if ( ! is_callable( array( $tmp_class, $action ) ) ) {
			$msg  = '<span style="color:red">The action <strong>';
			$msg .= $action . '</strong> could not be called from the controller <strong>';
			$msg .= $class_name . '</strong></span>';
			die( $msg );
			//header( 'location:' . HOME_URL . 'error' );
		}
		$tmp_class->$action( $params );

		/*
		 * View
		 * ----------------------------------------------------------------------
	    */
		self::render( $controller, $action );
	}

	public static function load_controller( $name ) {
		$controller_path = APP_PATH . 'controllers/' . $name . '_controller.php';
		if ( ! file_exists(  $controller_path ) ) 
		{
			$msg = '<span style="color:red">The file';
			$msg .= '<strong>' . $name . '_controller.php</strong>';
			$msg .= 'could not be found at ';
			$msg .= '<pre>' . $controller_path . '</pre></span>';
			die( $msg );
			//header( 'location:' . HOME_URL . 'error' );
		}
		include $controller_path;	
	}

	/*
	 * Helper function
	 * -----------------------------------------------------------------------
	*/
	public static function render( $controller, $action ) {
		$view_path   = self::view_path( $controller, $action );
		$layout_path = self::get_layout( $controller, $action );
		$main_path   = APP_PATH  . 'views/' . THEME_NAME . '/layouts/main.php';
		$file_main   = BASE_PATH . 'tmp/main_' . time() . '.php';
		
		$view = self::getViewContent( $view_path );
		$layout = self::getLayoutContent( $layout_path );
		$view_layout = self::combineViewLayout( $view, $layout );		
		$main = self::getMainContent( $main_path );
		$view_layout_main = self::combineViewLayoutMain( $view_layout, $main );
		
		self::write_file( $file_main, $view_layout_main );
		unset( $view_layout_main );

		//Extract Global Variables
		extract( self::$user_vars );

		//Plugins
		include APP_PATH . 'views/' . THEME_NAME . '/plugins/plugins.php';
		
		//Functions
		include APP_PATH  . 'views/' . THEME_NAME . '/modules/functions.php';
		
		//Load View
		include $file_main;

		//delete text file
		unlink( $file_main );
	}
	
	private static function getViewContent( $view_path ) {
		//get view content
		$view = self::get_content( $view_path  );
		if ( $view == null ) die( '<span style="color:red">View file not found</span>' );
		return $view;
	}
	
	private static function getLayoutContent( $layout_path ) {
		//get layout content
		$layout = self::get_content( $layout_path );
		if ( $layout == null ) die( '<span style="color:red">Layout file not found</span>' );
		return $layout;
	}
	
	private static function combineViewLayout( $view, $layout ) {
		//combine - view + layout
		$view_layout = str_replace( '[%CONTENT%]', $view, $layout );
		unset( $view, $layout );
		return $view_layout;
	}
	
	private static function getMainContent( $main_path ) {
		//get main content
		$main = file_get_contents( $main_path );
		if ( $main == null ) die( '<span style="color:red">Main file not found</span>' );
		return $main;
	}
	
	private static function combineViewLayoutMain( $view_layout, $main ) {
		//combine - view + layout + main
		$view_layout_main = str_replace( '[%LAYOUT_CONTENT%]', $view_layout, $main );
		return $view_layout_main;
	}

	public static function view_path( $controller, $action ) {
		if ( empty( self::$user_vars['view'] ) ) {
			$msg = '<span style="color:red">The view file of<strong> ' . $controller . ' controller</strong>';
			$msg .= ' is not defined';
			die( $msg );	
		}

		$view_path = APP_PATH . 'views/' . THEME_NAME . '/' . $controller . '/' . self::$user_vars['view'] . '.php';
		try {
			if ( ! file_exists( $view_path ) ) {
				$msg = 'The file ' . $action . '.php could not be found at ' . $view_path;
				throw new CustomException( $msg );
			}
		} catch( CustomException $e ) {
			$e->handle();
		}
		return $view_path;
	}

	public static function get_layout( $controller, $action ) {
		$layout = ( isset( self::$user_vars['layout'] ) ) ? self::$user_vars['layout'] . '.php' : 'layout.php';
		$path = APP_PATH . 'views/' . THEME_NAME . '/layouts/' . $layout;

		if ( ! file_exists( $path ) )
			die( '<span style="color:red">Layout file not found</span>' );
		return $path;
	}

	public static function get_content( $path ) {
		if ( ! file_exists( $path ) )
			$content = null;
		else
			$content = file_get_contents( $path );
		return $content;
	}

	public static function write_file( $filename, $layout_content ) {
		if ( ! file_exists( BASE_PATH . 'tmp' ) )
        	mkdir( BASE_PATH . 'tmp', 0777, true );

		$file = fopen( $filename, 'w' );
		fwrite( $file, $layout_content );
		fclose( $file );
	}
}
