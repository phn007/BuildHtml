<?php
trait SitemapIndex {
	use SitemapIndexFileAndDirectory;
	use SitemapIndexXmlTags;

	private $sitemapUrl;

	function createSitemapIndex() {
		date_default_timezone_set ( 'America/Los_Angeles' );
		$this->openIndexXmlTagHeaderUrlSet();
		$files = $this->readTextFileFromSitemapDir();

		foreach ( $files as $file ) {
			$this->sitemapUrl = $this->setSitemapIndexUrl( $file );
			$this->writeSitemapIndexEntry();
		}
		$this->closeIndexXmlTag();
		$this->printSitemapIndexResult();
	}

	function printSitemapIndexResult() {
		echo $this->sitemapIndexFile();
		echo "\n";
	}

	function sitemapIndexLastmod() {
		return date ( "Y-m-d" );
	}

	function setSitemapIndexUrl( $file ) {
		$arr = explode( '/', $file );
		$filename = end( $arr );
		return $this->homeUrl() . '/xml/' . $filename;
	}
}

trait SitemapIndexFileAndDirectory {
	function readTextFileFromSitemapDir() {
    	return glob( $this->sitemapDir() . '*.xml' );
    }

    function sitemapIndexFile() {
    	return $this->sitemapIndexDestination() . 'sitemap_index.xml';
    }

    function sitemapIndexDestination() {
    	return TEXTSITE_PATH . $this->config['project'] . '/' . $this->config['site_dir'] . '/';
    }

    function sitemapDir() {
    	return TEXTSITE_PATH . $this->config['project'] . '/' . $this->config['site_dir'] . '/xml/';
    }
}

trait SitemapIndexXmlTags {
	function openIndexXmlTagHeaderUrlSet() {
		$this->fh = fopen( $this->sitemapIndexFile(), 'w' );
      	fwrite( $this->fh, $this->sitemapIndexXmlHeader() );
	}

    function sitemapIndexXmlHeader() {
    	return '<?xml version="1.0" encoding="UTF-8"?>'  . PHP_EOL .
      	'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
    }

    function writeSitemapIndexEntry() {
    	$xml = '<sitemap>'  . PHP_EOL;
		$xml .= '<loc>' . $this->sitemapUrl . '</loc>'  . PHP_EOL;
		$xml .= '<lastmod>' . $this->sitemapIndexLastmod() . '</lastmod>'  . PHP_EOL;
		$xml .= '</sitemap>'  . PHP_EOL;
		fwrite( $this->fh, $xml );
    }

    function closeIndexXmlTag() {
    	$xml = '</sitemapindex>';
      	fwrite( $this->fh, $xml );
      	fclose( $this->fh );
    }
}