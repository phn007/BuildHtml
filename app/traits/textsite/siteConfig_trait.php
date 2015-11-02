<?php
use webtools\libs\Helper;
trait SiteConfig {

	function createSiteConfig() {
		$destination = $this->setDestinationPath();
		Helper::make_dir( $destination );
		file_put_contents( 
			$destination . 'config.php', 
			'<?php' . PHP_EOL . '$cfg = ' . var_export( $this->config, true) . ';' 
		);
		$this->printResult( $destination );
	}

	function setDestinationPath() {
		return TEXTSITE_PATH . $this->config['project'] . '/' . $this->config['site_dir'] . '/config/';
	}

	function printResult( $destination ) {
		echo "Create config file: ";
		echo $destination . 'config.php';
		echo " done...\n";
	}
}

