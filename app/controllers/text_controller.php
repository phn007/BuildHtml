<?php
use webtools\controller;

include WT_BASE_PATH . 'libs/csvReaderV2.php';
include WT_APP_PATH . 'traits/getCsvConfigDataV2_trait.php';
include WT_APP_PATH . 'traits/setupConfigV4_trait.php';
include WT_APP_PATH . 'traits/textsite/siteInfo_trait.php';

/**
 * Create Textsite
 * -------------------
 * -r copy route php file
 * -z zip files
 *
 * php text create all demo domain.com
 * php text create code demo domain.com
 * php text create config demo domain.com
 * php text create htaccess demo domain.com
 * php text create sitemap demo domain.com
 * php text create sitemap_index demo domain.com
 * php text create robots demo domain.com
 * php text create theme demo domain.com
 *
 * php text create zip demo domain.com
*/
class TextController extends Controller {
	use GetCsvConfigData;
	use SetupConfig;
	use SiteInfomation;
	
	function create( $function, $params, $options ) {
		$csvFilename = $params['csvFilename'];
		$domain = $params['domain'];

		if ( array_key_exists( 'zip', $options ) ) 
			$option = 'zip';
		else
			$option = null;

		$csvData = $this->initialConfigDataFromCsvFileForText( $csvFilename, $domain );
		$iniFilename = $csvData['config_file'];

		$this->initialDotINIConfigFile( $iniFilename, $csvFilename );
		$config = $this->getSiteConfigDataForText( $csvFilename, $csvData );
		
		if ( $config['hostname'] == 's3' ) {
			$config['domain'] = str_replace( 'http://', 'https://', $config['domain']);
		}

		$config['site_desc']   = $this->getSiteDescription( $config['site_category'] ); //SiteInfomation Trait
		$config['site_author'] = $this->getSiteAuthor();
		$config['prod_route']  = $this->getProdRoute();

		$model = $this->model( 'textsite' );
		$model->initialTextsite( $config, $options );

		if ( $function == 'code' || $function == 'all' ) $model->code();
		if ( $function == 'config' || $function == 'all' ) $model->siteConfig();
		if ( $function == 'htaccess' || $function == 'all' ) $model->htaccess();
		if ( $function == 'sitemap' || $function == 'all' ) $model->sitemap();
		if ( $function == 'sitemapindex' || $function == 'all' ) $model->sitemapIndex();
		if ( $function == 'robots' || $function == 'all' ) $model->robots();
		if ( $function == 'theme') $model->theme();
		if ( $function == 'zip') $model->zipFiles();
		if ( $option == 'zip') $model->zipFiles();
	}

	/**
	 * php text server start csvFilename domain
	 * php text server start rexce domain.com
	 */
	function server( $function, $params, $options ) {
		$csvFilename = $params['csvFilename'];
		$domain = $params['domain'];
		$csvData = $this->initialConfigDataFromCsvFileForText( $csvFilename, $domain );
		$config = array(
			'project' => $csvFilename,
			'siteDir' => $csvData['site_dir'],
			'hostname'  => $csvData['domain']
		);
		$model = $this->model( 'textsite' );
		if ( 'start' == $function ) $model->serverStart( $config );
	}

	/**
	 * php text db del demo
	 */
	function DB( $function, $params, $options ) {
		if ( $function == 'del' ) {
			$iniFilename = $params['iniFilename'];
			$dbs = $this->getDatabaseNames( $iniFilename );
			$model = $this->model( 'textsite' );
			$model->deleteDatabase( $dbs );
		}
		echo "\n";
	}
}//class