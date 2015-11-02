<?php
use webtools\controller;
include WT_BASE_PATH . 'libs/csvReaderV2.php';
include WT_APP_PATH . 'traits/getCsvConfigDataV2_trait.php';
include WT_APP_PATH . 'traits/setupConfigV4_trait.php';

/**
 * Example commandline
 * -------------------
 * php html build all csvFilename localhost:8001
 */
class HtmlController extends Controller {
	use GetCsvConfigData;
	use SetupConfig;

	/**
	 * php html build all csvFilename domain.com
	 */
	function build( $function, $params, $options ) {
		$csvFilename = $params['csvFilename'];
		$domain = $params['domain'];

		$csvData = $this->initialConfigDataFromCsvFileForText( $csvFilename, $domain );
		$iniFilename = $csvData['config_file'];
		$this->initialDotINIConfigFile( $iniFilename, $csvFilename );
		$config = $this->getSiteConfigDataForHtml( $csvData );
		$model = $this->model('html');
		$model->initialVariables( $config );
		if ( 'all' == $function || 'files' == $function ) $model->files();
		if ( 'all' == $function || 'homepage' == $function ) $model->homePage();
		if ( 'all' == $function || 'categoriespage' == $function ) $model->categoriesPage();
		if ( 'all' == $function || 'brandspage' == $function ) $model->brandsPage();
		if ( 'all' == $function || 'staticpage' == $function ) $model->staticPage();
		if ( 'all' == $function || 'categorypage' == $function ) $model->categoryPage();
		if ( 'all' == $function || 'brandpage' == $function ) $model->brandPage();
		if ( 'all' == $function || 'productpage' == $function ) $model->productPage();

		echo "\n";
	}

	/**
	 * php html server start csvFilename domain
	 * php html server start demo domain.com
	 */
	function server( $function, $params, $options ) {
		$csvFilename = $params['csvFilename'];
		$domain = $params['domain'];
		$csvData = $this->initialConfigDataFromCsvFileForText( $csvFilename, $domain );
		$config = array(
			'project' => $csvFilename,
			'siteDir' => $csvData['site_dir'] . '/_html-site',
			'hostname'  => $csvData['domain']
		);
		$model = $this->model( 'textsite' );
		if ( 'start' == $function ) $model->serverStart( $config );
	}

}