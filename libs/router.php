<?php
namespace webtools\libs;

 class Router {
	public static function dispatch( $options ) {
		$controller = $options['controller'];
		$action = $options['action'];
		$functions = $options['function'];
		$params	= $options['params'];
		$options = $options['options'];
		
		//โหลด controller ที่ user ส่งเข้ามา
		self::load_controller( $controller );

		//กำหนดรูปแบบชื่อของ Controller
		$class_name = ucfirst( $controller ) . 'Controller';

		//ตรวจสอบว่ามี class อยู่หรือเปล่า
		if ( ! class_exists( "$class_name" ) )
			die( "\n" . $class_name . " class not found! \n\n" );
		
		$class = "$class_name";
		$tmp_class = new $class();

		//ตรวสอบว่าใน class มี action อยู่หรือเปล่า
		if ( ! is_callable( array( $tmp_class, $action ) ) )
			die( "\nThe action " . $action . " could not be called from the controller\n\n" );
		
		$tmp_class->$action( $functions, $params, $options );	
	}

	public static function load_controller( $control ) {
		//define path
		$controller_path = WT_APP_PATH . 'controllers/' . $control . '_controller.php';

		//check path
		if ( ! file_exists(  $controller_path ) )
			die( "\n" . $control . " controller file not found!\n\n" );
		
		include $controller_path;
	}

}//class
