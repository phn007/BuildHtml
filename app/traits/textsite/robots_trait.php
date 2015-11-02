<?php
use webtools\libs\Helper;

trait Robots {

	function createRobots() {
		Helper::make_dir( $this->robotDestination() );

		$fh = fopen(  $this->robotDestination() . '/robots.txt' , 'w' );
		$robot  = 'User-agent: *' . PHP_EOL;
		$robot .= 'Disallow: /shop/' . PHP_EOL;
		$robot .= 'Sitemap: ' . $this->getDomain() . '/sitemap_index.xml';
		fwrite( $fh, $robot );
		fclose( $fh );

		$this->printRobotsResult();
	}

	function printRobotsResult() {
		echo 'created: ' . $this->robotDestination() . '/robots.txt';
		echo "\n";
	}

	function getDomain() {
		return rtrim( $this->config['domain'], '/' );
	}

	function robotDestination() {
		return TEXTSITE_PATH . $this->config['project'] . '/' . $this->config['site_dir'];
	}
}