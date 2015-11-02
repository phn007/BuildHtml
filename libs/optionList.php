<?php
//textdb -c ddb2.ini create product param1 param2
//textdb -c ddb2.ini create brand
//textdb -c ddb2.ini create all

/*
controller: 	textdb
option: 		-c 
option-value: 	ddb2.ini
action:     	create
function: 		product
params: 		param1 param2
*/

class OptionList {

	function site() {
		$textSiteAction = 'create';
		$textSiteFunction['functions'] = array( 
			'all' => array( 'csvFilename' ),
			'textdb' => array( 'csvFilename' ),
			'textsite' => array( 'csvFilename' ),
			'zip' => array( 'csvFilename' )
		);

		$uploadAction = 'ftp';
		$uploadFunction['functions'] = array( 'upload' => array( 'csvFilename' ) );
		return array( 
			$textSiteAction => $textSiteFunction,
			$uploadAction => $uploadFunction
		);
	}

	function textdb() {
		$textdbAction = 'create';
		$textdbFunction['functions'] = array( '*' => array('csvFilename', 'iniFilename') );

		$separatorAction = 'separator';
		$separatorFunction['functions'] = array( 'add' => array( 'iniFilename') );

		$calcAction = 'calc';
		$calcFunction['functions'] = array( '*' => array( 'iniFilename', 'number') );

		$delDbAction = 'db';
		$delDbFunction['functions'] = array( 'del' => array( 'iniFilename' ) );

		return array(
			$textdbAction => $textdbFunction,
			$calcAction => $calcFunction,
			$separatorAction => $separatorFunction,
			$delDbAction => $delDbFunction
		);	
	}

	function text() {
		$action = 'create';
		$function['functions'] = array( '*' => array( 'csvFilename', 'domain' ) );

		$serverAction = 'server';
		$serverFunction['functions'] = array( 'start' => array( 'csvFilename', 'domain' ) );

		return array( 
			$action => $function, 
			$serverAction => $serverFunction
		);
	}
	
	function textsite() {
		$createAction = 'create';
		$createFunction['functions'] = array(
			'textdb'    => array(),
			'textdbNew' => array(),
			'code'      => array(),
			'htaccess'  => array(),
			'logo'      => array(),
			'sitemap'   => array(),
			'sitemapindex' => array(),
			'config'    => array(),
			'robots'    => array(),
			'siteall'   => array(),
			'all'       => array(),
			'theme'     => array(),
			'zip'       => array()
		);

		$serverAction = 'server';
		$serverFunction['functions'] = array( 'start' => array() );

		$showAction = 'show';
		$showFunction['functions'] = array( 'config' => array() );

		$calcAction = 'calc';
		$calcFunction['functions'] = array( '*' => array('number') );

		$calculateAction = 'calculate';
		$calculateFunction['functions'] = array( '*' => array('number') );

		$delDbAction = 'db';
		$delDbFunction['functions'] = array( 'del' => array() );

		$separatorAction = 'separator';
		$deparatorFunction['functions'] = array( 'check' => array() );

		return array( 
			$createAction => $createFunction, 
			$serverAction => $serverFunction,
			$showAction => $showFunction,
			$calculateAction => $calculateFunction,
			$calcAction => $calculateFunction,
			$delDbAction  => $delDbFunction,
			$separatorAction => $deparatorFunction
		);
	}

	function html() {
		$action = 'build';
		$functions['functions'] = array( 
			'homepage' => array( 'csvFilename', 'domain' ),
			'productpage' => array( 'csvFilename', 'domain' ),
			'categorypage' => array( 'csvFilename', 'domain' ),
			'brandpage' => array( 'csvFilename', 'domain' ),
			'categoriespage' => array( 'csvFilename', 'domain' ),
			'brandspage' => array( 'csvFilename', 'domain' ),
			'staticpage' => array( 'csvFilename', 'domain' ),
			'files' => array( 'csvFilename', 'domain' ),
			'all' => array( 'csvFilename', 'domain' )
		);

		$serverAction = 'server';
		$serverFunction['functions'] = array( 'start' => array( 'csvFilename', 'domain' ) );

		return array( $action => $functions, $serverAction => $serverFunction);
	}

	function prospapi() {
		$action = 'get';
		$functions['functions'] = array( 
			'category' => array( 'iniFilename' ),
			'brand' => array( 'iniFilename' ),
			'product' => array( 'iniFilename' ),
			'all' => array( 'iniFilename' )
		);
		return array( $action => $functions );
	}

	function scrape() {
		$getAction = 'get';
		$getFunctions['functions'] = array( '*' => array( 'row', 'page' ) );
		
		$delAction = 'del';
		$delFunction['functions'] = array(
			'db' => array( 'merchant' ),
			'tb' => array( 'merchant' )
		);

		$getDetailAction = 'getdetail';
		$getDetailFunctions['functions'] = array( '*' => array( 'merchant' ) );
		return array( 
			$getAction => $getFunctions, 
			$delAction => $delFunction,
			$getDetailAction => $getDetailFunctions
		);
	}

	function host() {
		$hostnineAction = 'hostnine';
		$hostnineFunction['functions'] = array(
			'terminate' => array( 'csvFilename' ),
			'create' => array( 'csvFilename' ),
			'modify' => array( 'csvFilename' )
		);
		return array( $hostnineAction => $hostnineFunction );
	}

	function hostnine() {
		$accountAction = 'accounts';
		$accountFunction['functions'] = array(
			'get' => array( 'account', 'domain' ),
			'create' => array( 'account', 'domain', 'username', 'password', 'location', 'package' ),
			'view' => array(),
			'terminate' => array( 'account', 'domain' ),
			'modify' => array( 'account', 'domain' )
		);

		$locationAction = 'locations';
		$locationFunction['functions'] = array( 'get' => array() );

		$packageAction = 'packages';
		$packageFunction['functions'] = array( 'get' => array( 'account' ) );

		return array(
			$accountAction => $accountFunction,
			$locationAction => $locationFunction,
			$packageAction => $packageFunction
		);
	}

	function statcounter() {
		$action = 'project';
		$functions['functions'] = array( 'add' => array( 'csvFilename' ) );
		return array( $action => $functions );
	}

	function ftp() {
		$uploadAction = 'upload';
		$uploadFunctions['functions'] = array( '*' => array( 'domain' ) ); //function = csvFilename
		return array( $uploadAction => $uploadFunctions );
	}
}
