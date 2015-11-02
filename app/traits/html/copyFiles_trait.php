<?php
trait Files {
	function copyFiles() {
		$this->run( $this->assets() );
		$this->run( $this->sitemap() );
		$this->run( $this->sitemapIndex() );
		$this->run( $this->robots() );
		$this->run( $this->images() );
	}

	function run( $data ) {
		$cloneCom = $this->component( 'clone' );
		$source = $data['source'];
		$destination = $data['destination'];
		$specificFiles = isset( $data['specificFiles'] ) ? $data['specificFiles'] : null;
		$mode = $data['mode'];
		$cloneCom->runClone( $source, $destination, $specificFiles, $mode );
	}

	function assets() {
		return array(
			'source' => $this->sourceDir . 'app/views/' . $this->config['theme_name'] . '/assets',
			'destination' => $this->sourceDir . '_html-site/app/views/' . $this->config['theme_name'] . '/assets',
			'mode' => 'fullMode'
		);
	}

	function sitemap() {
		return array(
			'source' => $this->sourceDir . 'xml',
			'destination' => $this->sourceDir . '_html-site/xml',
			'mode' => 'fullMode'
		);
	}

	function sitemapIndex() {
		return array(
			'source' => rtrim( $this->sourceDir, '/'),
			'destination' => $this->sourceDir . '_html-site',
			'specificFiles' => array( $this->sourceDir . 'sitemap_index.xml' ),
			'mode' => 'includeMode'
		);
	}

	function images() {
		return array(
			'source' => $this->sourceDir . 'images',
			'destination' => $this->sourceDir . '_html-site/images',
			'mode' => 'fullMode'
		);
	}

	function robots() {
		return array(
			'source' => rtrim( $this->sourceDir, '/'),
			'destination' => $this->sourceDir . '_html-site',
			'specificFiles' => array( $this->sourceDir . 'robots.txt' ),
			'mode' => 'includeMode'
		);
	}
}