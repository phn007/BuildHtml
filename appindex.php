<?php
ini_set('MAX_EXECUTION_TIME', -1);
//error_reporting(0);
error_reporting( E_ALL );

class AppIndex
{
	public function __construct( $options )
	{
		define( 'WT_BASE_PATH', dirname( realpath( __FILE__ ) ) . '/' );
		define( 'WT_APP_PATH', WT_BASE_PATH . 'app/' );
		define( 'CONSOLE_PATH', WT_BASE_PATH . '_console/');
		define( 'FILES_PATH', WT_BASE_PATH . 'files/' );

		//define( "HOST_PATH", 'C:\xampp\htdocs/' );
		//define( "HOST_PATH", '/var/www/' );

		//Site Generator
		//define( "CALL_CREATOR", 'sitecreator' );
		define( 'SITE_CREATOR_PATH', WT_BASE_PATH . 'sitecreator/');


		define( 'TEXTDB_PATH', '../textdb/' );
		//define( 'TEXTSITE_PATH', '../textsite/' );
		define( 'TEXTSITE_PATH', dirname( WT_BASE_PATH ) . '/textsite/' );
		define( 'CONFIG_PATH', WT_BASE_PATH . 'configs/' );

		//TextSpinner
		define( 'TEXTSPIN_PATH', FILES_PATH . 'textspinner/');

		define( 'EMPTY_BRAND_NAME', 'Default' );
		define( 'EMPTY_CATEGORY_NAME', 'General' );

		include 'libs/controller.php';
		include 'libs/router.php';
		include 'libs/database.php';
		include 'libs/helper.php';
		//include 'libs/networkSupport.class.php';
		include CONSOLE_PATH . 'Lite.php';

		webtools\libs\Router::dispatch( $options );
	}
}
new AppIndex( $options );
