<?php
use webtools\controller;
use webtools\libs\Helper;

include WT_APP_PATH . 'traits/textsite/code_trait.php';
include WT_APP_PATH . 'traits/textsite/siteConfig_trait.php';
include WT_APP_PATH . 'traits/textsite/htaccess_trait.php';
include WT_APP_PATH . 'traits/textsite/sitemap_trait.php';
include WT_APP_PATH . 'traits/textsite/sitemapIndex_trait.php';
include WT_APP_PATH . 'traits/textsite/robots_trait.php';
include WT_APP_PATH . 'traits/textsite/logo_trait.php';
include WT_APP_PATH . 'traits/textsite/server_trait.php';
include WT_APP_PATH . 'traits/textsite/zip_trait.php';
//include WT_APP_PATH . 'traits/textsite/deleteDatabase_trait.php';
//include WT_APP_PATH . 'traits/textsite/checkSeparator_trait.php';

class TextsiteModel extends Controller {
	use Code;
	use SiteConfig;
	use Htaccess;
	use Sitemap;
	use SitemapIndex;
	use Robots;
	use Logo;
	use Server;
	use ZipFiles;
	//use DeleteDatabase;
	//use CheckSeparator;

	private $config;
	private $cloneCom;

	function initialTextsite( $config, $options ) {
		$this->config = $config;
		$this->options = $options;
	}

	function code() {
		$this->cloneCom = $this->component( 'clone' );
		$this->getAllExceptViews();
		$this->getViews();
		
		if ( isset( $this->options['r'] ) ) 
			$this->getRouteFileForDevelopment();
	}

	function siteConfig() {
		$this->createSiteConfig();
	}

	function htaccess() {
		$this->createHtaccess();
	}

	function sitemap() {
		$this->createSitemap();
	}

	function sitemapIndex() {
		$this->createSitemapIndex();
	}

	function robots() {
		$this->createRobots();
	}

	function logo() {
		$this->createLogo();
	}

	function theme() {
		$this->cloneCom = $this->component( 'clone' );
		$this->getViews();
	}

	function serverStart( $siteConfigData ) {
		$this->runServer( $siteConfigData );
	}

	function zipFiles() {
		$this->runZipFiles();
	}

}