<?php
class Map extends Object {
	static function dispatch( $controller, $action, $params ) {
		self::load_controller( $controller );
		$class_name = ucfirst( $controller ) . 'Controller';

		//Create Class Objext
		if ( ! class_exists( $class_name ) ) {
			$msg = 'My Debug: ' . $class_name . ' Not Found!!!';
			trigger_error( $msg, $error_type = E_USER_ERROR );
		}
		$tmp_class = new $class_name();

		//Call Action
		if ( ! is_callable( array( $tmp_class, $action ) ) ) {
			$msg = "is_callable return false : dispatch function";
			trigger_error( $msg, $error_type = E_USER_ERROR );
		}
		$tmp_class->$action( $params );	

		//Render
		self::render( $controller, $action, $params );
	}

	static function load_controller( $name ) {
		$controller_path = APP_PATH . 'controllers/' . $name . '_controller.php';
		if ( ! file_exists(  $controller_path ) ) {
			$msg = "router -> load_controller: ";
			$msg .= "The file " . $name . "_controller.php could not be found at " . $controller_path;
			trigger_error( $msg, $error_type = E_USER_ERROR );
		}
		include_once $controller_path;
	}

	static function render( $controller, $action, $params ) {
		$viewPath   = self::viewPath( $controller, $action );
		$layoutPath = self::getLayout( $controller, $action );
		$mainPath   = APP_PATH  . 'views/' . THEME_NAME . '/layouts/main.php';
		$filePath   = BASE_PATH . 'tmp/main_' . time() . '.php';

		$view = self::getViewContent( $viewPath );
		$layout = self::getLayoutContent( $layoutPath );
		$viewLayou = self::combineViewLayout( $view, $layout );		
		$main = self::getMainContent( $mainPath );
		$viewLayoutMain = self::combineViewLayoutMain( $viewLayou, $main );
		
		self::write_file( $filePath, $viewLayoutMain );
		unset( $viewLayoutMain );
		
		extract( self::$user_vars );
		include APP_PATH  . 'views/' . THEME_NAME . '/modules/functions.php';

		$savePath = self::setSavePath( $params );

		ob_start();
		include $filePath;
		$cache  = ob_get_contents();
		file_put_contents( $savePath, $cache );
		ob_end_clean();
		unlink( $filePath );

		echo $savePath;
		echo "\n";
	}

	/**
	 * HELPER
	 */
	private static function setSavePath( $params ) {
		extract( self::$user_vars );
		if ( $currentPage == 'product-page' ) {
			if ( isset( $params[0] ) ) $productFile = $params[0];
			if ( isset( $params[1] ) ) $productKey = $params[1];
			$path = BASE_PATH . '_html-site/' . $productFile . '/';
			Helper::make_dir( $path );
			return $path . $productKey . FORMAT;
		}

		if ( $currentPage == 'home-page' ) {
			$path = BASE_PATH . '_html-site/';
			Helper::make_dir( $path );
			return $path . 'index' . FORMAT;
		}

		if ( $currentPage == 'category-page' ) {
			if ( isset( $params[0] ) ) $catSlug = $params[0];
			if ( isset( $params[1] ) ) $page = $params[1];
			$path = BASE_PATH . '_html-site/category/' . $catSlug . '/';
			Helper::make_dir( $path );
			return $path . $page . FORMAT;
		}
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

	public static function viewPath( $controller, $action ) {
		if ( empty( self::$user_vars['view'] ) )
		{
			$msg = '<span style="color:red">The view file of<strong> ' . $controller . ' controller</strong>';
			$msg .= ' is not defined';
			die( $msg );	
		}
		$view_path = APP_PATH . 'views/' . THEME_NAME . '/' . $controller . '/' . self::$user_vars['view'] . '.php';
		
		if ( ! file_exists( $view_path ) ) 
		{
			$msg = '<span style="color:red">The file <strong>' . $action . '.php</strong>';
			$msg .= ' could not be found at <pre>' . $view_path . '</pre></span>';
			die( $msg );
		}
		return $view_path;
	}

	public static function getLayout( $controller, $action ) {
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