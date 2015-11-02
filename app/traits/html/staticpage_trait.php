<?php
trait StaticPage {
	function buildStaticPage() {
		//$this->includeDefineSiteConfig(); //CategoryPage Trait
		$this->runBuildStaticPage( 'about' );
		$this->runBuildStaticPage( 'contact' );
		$this->runBuildStaticPage( 'privacy' );
	}

	function runBuildStaticPage( $pageName ) {
		$projectName = $this->config['project'];
		$siteDirName = $this->config['site_dir'];
		$controller = 'StaticPage';
    	$action = $pageName;
		$command  = 'php '. WT_BASE_PATH . 'buildhtml/app.php ';
		$command .= $projectName . ' ';
		$command .= $siteDirName . ' ';
		$command .= $controller . ' ';
		$command .= $action . ' ';
		echo shell_exec( $command );
	}
}