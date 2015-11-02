<?php
use webtools\libs\Helper;

trait Sitemap {
	use XmlTags;
	use XmlFileAndDirectory;
	use SetPermalink;

	private $fh;
	private $countSitemapFileNumber;
	private $countProductPerFile;

	private $countXmlFile;
	private $countAllProducts;

	private $productFile;
	private $productKey;

	function initialCountVariables() {
		$this->countSitemapFileNumber = 1;
		$this->countProductPerFile = 1;
		$this->countXmlFile = 1;
		$this->countAllProducts = 1;
	}

	function createSitemap() {
		Helper::make_dir( $this->sitemapDestination() );
		date_default_timezone_set ( 'America/Los_Angeles' );
		$this->initialCountVariables();

		$this->openXmlTagHeaderUrlSet();
		foreach ( $this->readProductTextFileFromDir() as $file ) {
			$this->productFile = $this->getProductFile( $file );
			$productItems = $this->readContentFromProductTextFile( $file );
			$this->loopThroughProductItems( $productItems );
			$this->countXmlFile++;
		}
		$this->closeXmlTageUrlSet();
		$this->printCreateXmlResult();
	}

	function loopThroughProductItems( $items ) {
		foreach ( $items as $key => $item ) {
			$this->productKey = $key;
			if ( $this->countProductPerFile > 5000 ) 
				$this->createXmlIfExceedLimit();
			else 
				$this->createXmlFile();
			$this->countProductPerFile++;
			$this->countAllProducts++;
		}
	}

	function createXmlFile() {
		$this->writeXmlEntryProductUrl();
	}

	function createXmlIfExceedLimit() {
		$this->closeXmlTageUrlSet(); //close previous file
		$this->printCreateXmlResult();
		$this->countSitemapFileNumber++;
		$this->countProductPerFile = 1; //reset

		$this->openXmlTagHeaderUrlSet(); //open new file
		$this->writeXmlEntryProductUrl();
	}

	function printCreateXmlResult() {
		echo $this->siteampFile();
		echo "\n";
	}	 
}

trait SetPermalink {
	function setPermalink() {
		$url = array(
			'homeUrl' => rtrim( $this->homeUrl(), '/' ),
			'productFile' => $this->productFile,
			'productKey' => $this->productKey . $this->format(),
		);
		return implode( '/', $url );
	}

	function homeUrl() {
		return $this->config['domain'];
	}

	function format() {
		return $this->config['url_format'];
	}
}

trait XmlFileAndDirectory {
	function getProductFile( $file ) {
		$filename = explode( '/', $file );
        $filename = end( $filename );
        return str_replace( '.txt', '', $filename );
	}

	function readProductTextFileFromDir() {
		return glob( $this->sitemapSource() . '*.txt' );
	}

	function readContentFromProductTextFile( $file ) {
		$str = file_get_contents( $file );
        return unserialize( $str );
	}

	function siteampFile() {
		return $this->sitemapDestination() . 'sitemap-' . $this->countSitemapFileNumber . '.xml';
	}

	function sitemapSource() {
		return TEXTSITE_PATH . $this->config['project'] . '/' . $this->config['site_dir'] . '/contents/products/';
	}

	function sitemapDestination() {
		return TEXTSITE_PATH . $this->config['project'] . '/' . $this->config['site_dir'] . '/xml/';
	} 
}


trait XmlTags {
	function xmlHeader() {
		$xml  = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
		$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
		$xml .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
		$xml .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 ';
		$xml .= 'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;
		return $xml;
	}

	function openXmlTagHeaderUrlSet() {
		$this->fh = fopen( $this->siteampFile(), 'w' );
      	fwrite( $this->fh, $this->xmlHeader() );
	}

	function closeXmlTageUrlSet() {
		$endxml = '</urlset>';
        fwrite( $this->fh, $endxml );
        fclose( $this->fh );
	}

	function getUrl() {
		if ( $this->countXmlFile == 1 && $this->countAllProducts == 1 )
			return $this->homeUrl() . '/index.html';
		else
			return $this->setPermalink();
	}  

	function writeXmlEntryProductUrl() {
		$entry = '<url>' . PHP_EOL .
        '<loc>' . $this->getUrl() . '</loc>'. PHP_EOL .
		'<lastmod>' . $this->getLastmod() . '</lastmod>' . PHP_EOL .
		'<changefreq>' . $this->getFreq() . '</changefreq>' . PHP_EOL .
		'<priority>' . $this->getPriority() . '</priority>' . PHP_EOL .
		'</url>' . PHP_EOL;
        fwrite( $this->fh, $entry );
	}

	function getLastmod() {
		return date ( "Y-m-d" );
	}

	function getFreq() {
		return  "daily";
	}

	function getPriority() {
		return "0.5";
	}
}
