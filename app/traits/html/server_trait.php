<?php
trait Server {
	function runServer( $config ) {
		$project  = $config['project'];
		$siteDir  =  $config['siteDir'];
		$hostname = $config['hostname'];
		$path = TEXTSITE_PATH . $project . '/' . $siteDir;

		shell_exec( 'php -S ' . $hostname . ' -t ' . $path . ' ' . $path . '/r.php' );
	}
}