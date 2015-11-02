<?php
use webtools\controller;
use webtools\libs\Helper;

include WT_BASE_PATH . 'libs/TablePrinter.php'; 
include WT_APP_PATH . 'extensions/scraper-class/_simpleHtmlDom.php';
include WT_APP_PATH . 'traits/getCsvConfigDataV2_trait.php';
include WT_BASE_PATH . 'libs/csvReaderV2.php';

class statcounterModel {
	use GetCsvConfigData;

	function add( $params, $options ) {
		$csvFilename = isset( $params['csvFilename'] ) ? $params['csvFilename'] : null;
		$row = isset( $options['row'] ) ? $options['row'] : null;

		$csvfile = $this->getDataFromCsvFile( $csvFilename, $row ); //see, getCsvConfigData Trait
		$prevUsername = null;
		foreach ( $csvfile as $data ) {
			if ( !empty( $data['domain'] ) ) {
				$data['domain'] = 'http://' . $data['domain']; // add http:// to domain
				if ( $prevUsername != $data['stat_user'] ) 
					$loginResult = $this->login( $data['stat_user'], $data['stat_pass'] );

				$addResult = $this->addNewProject( $loginResult, $data );
				$securityCode = $this->getSecurityCode( $addResult );
				$this->writeFile( $csvFilename, $data, $securityCode );
				$this->printResult( $data, $securityCode );
				$prevUsername = $data['stat_user'];
			}
		}	
	}

	function printResult( $data, $code ) {
		$content  = $data['stat_user'] . ', ';
		$content .= $data['group'] . ', ';
		$content .= $data['project'] . ', ';
		$content .= $data['domain'] . ', ';
		$content .= $code['sc_id'] . '#' . $code['sc_code'];
		echo $content;
		echo "\n";
	}

	function writeFile( $csvFilename, $data, $securityCode ) {
		$w = new WriteResult();
		$w->write( $csvFilename, $data, $securityCode );
	}

	function getSecurityCode( $addResult ) {
		$scc = new SecurityCode();
		return $scc->getCode( $addResult );
	}

	function addNewProject( $loginResult, $data ) {
		$add = new AddNewProject();
		return $add->addProject( $loginResult, array( 
						'projectname' 	 => $data['project'],
						'website_domain' => $data['domain'],
						'group_name'	 => $data['group'],
					)
				);
	}

	function login( $username, $password ) {
		$login = new Login( $username, $password );
		return $login->runLogin();
	}
}

class WriteResult {
	function write( $csvFilename, $data, $securityCode ) {
		$filename = $this->getFilename( $csvFilename );
		$file = $this->getFilePath( $filename );
		$content = $this->getContent( $data, $securityCode );
		file_put_contents( $file, $content, FILE_APPEND | LOCK_EX );
	}

	function getContent( $data, $code ) {
		return $data['domain'] . '|' . $code['sc_id'] . "#" . $code['sc_code'] . PHP_EOL;
	}

	function getFilePath( $filename ) {
		return FILES_PATH . 'statcounter/' . $filename;
	}

	function getFilename( $csvFilename ) {
		return $csvFilename . '-stat.txt';
	}
}

class SecurityCode {
	function getCode( $addResult ) {
		$ch = $addResult['ch'];
		$result = $this->getPageContent( $ch, $addResult );
		return $this->parse_security_code( $result );
	}

	function getPageContent( $ch, $addResult ) {
		curl_setopt( $ch, CURLOPT_URL, $addResult['url'] );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_REFERER, $addResult['referer'] );
		return curl_exec ( $ch );
	}
	
	private function parse_security_code( $content ) {
		$html = str_get_html( $content );
		if ( ! empty( $html ) ) {
			$str  = $html->find( 'div[id=stats-wrapper]', 0 );
			$sc_id = $this->getProjectId( $str );
	        $sc_code = $this->getSecurityCode( $str );
			$html->clear();
			unset($html);
			return array(
				'sc_id'   => $sc_id,
				'sc_code' => $sc_code
			);
		}
	}

	private function getSecurityCode( $str ) {
		$find = '/Current Security Code:<\/label>(.+?)<form action="" method="POST">/';
	    $matches = array();
	    preg_match( $find, $str, $matches );
	    return trim( $matches[1] );
	}

	private function getProjectId( $str ) {
		$find = '/Project ID:<\/label>(.+?)<label for="null">/';
	    $matches = array();
	    preg_match( $find, $str, $matches );
	    return trim( $matches[1] );
	}
}

class AddNewProject {
	function addProject( $loginResult, $data ) {
		$ch = $loginResult['ch'];
		$referer = $loginResult['eff_url'];
		$params = $this->getParams();
		$params['projectname']    = $data['projectname'];
		$params['website_domain'] = $data['website_domain'];
		$params['group_name'] 	  = $data['group_name'];
		$params = http_build_query( $params );
		return $this->postData( $ch, $referer, $params );
	}

	function postData( $ch, $referer, $params ) {
		curl_setopt( $ch, CURLOPT_URL, $this->addUrl() );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_REFERER, $referer );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
		curl_setopt( $ch, CURLOPT_POST, 1);

		$output = curl_exec ( $ch );
		$eff  = curl_getinfo( $ch, CURLINFO_EFFECTIVE_URL );
		$arr  = explode( 'project_id=', $eff );
		$p_id = end( $arr );

		return array(
			'ch' => $ch,
			'referer' => $eff,
			'url' => 'http://statcounter.com/p' . $p_id . '/security/' 
		);
	}

	function addUrl() {
		return 'http://statcounter.com/add-project/';
	}

	function getParams() {
		return array(
			'addmyproject'				=> '',
			'bg_color'					=> '#000000',
			'black_foreground'			=> 1,
			'country'					=> 'Thailand',
			'digit_color'				=> '#ffffff',
			'digit_grouping'			=> 'grouping_2',
			'display_type'				=> 'invisible',
			'email_report_type'			=> 'none',
			'font_size'					=> 3,
			'group_name'				=> '', #1
			'group_name_hidden'			=> '',
			'min_num_digits'			=> 8,
			'projectname'				=> 'ProjectTitle', #2
			'report_recipient[]'		=> 'lapc1401@gmail.com',
			'starting_count'			=> 0,
			'statcounter_button_hidden'	=> 201,
			'text_image'				=> 'image',
			'timezone'					=> 'Asia/Bangkok',
			'transparent_background'	=> 1,
			'view_my_stats_custom'		=> 'View My Stats',
			'visitor_pageload'			=> 'pageloads',
			'website_domain'			=> 'http://project-url' #3
		);
	}
}

class Login {
	function __construct( $username, $password ) {
		$this->username = $username;
		$this->password = $password;
	}

	function postData() {
		$postData = array( 
			'form_user' => $this->username,
			'form_pass' => $this->password
		);
		return http_build_query( $postData );
	}

	function url() {
		return 'http://statcounter.com/';
	}

	function referer() {
		return 'http://statcounter.com/';
	}

	function cookieFile() {
		return 'cookie.txt';
	}

	function httpAgent() {
		return 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0';
	}

	function runLogin() {
		# Check to make sure cURL is avaiable
		if( !function_exists( 'curl_init' ) || ! function_exists( 'curl_exec' ) )
			echo "cUrl is not available in you PHP server.";
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $this->url() );
		curl_setopt( $ch, CURLOPT_COOKIEJAR, $this->cookieFile() );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_USERAGENT, $this->httpAgent() );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 60 );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_REFERER, $this->referer() );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $this->postData() );
		curl_setopt( $ch, CURLOPT_POST, 1);
		curl_exec ( $ch );
		$eff = curl_getinfo( $ch, CURLINFO_EFFECTIVE_URL );
		$rs = array( 'ch' => $ch, 'eff_url' => $eff );
		return $rs;
	}
}