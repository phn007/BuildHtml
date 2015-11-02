<?php
use webtools\controller;
use webtools\libs\Helper;

class SiteModel {
	use TextDbCommandLine;
	use TextsiteCommandLine;
	use ZipTextsiteCommandLine;

	function textDb( $module, $initConfigData, $csvFilename, $row ) {
		$this->textDbCommandLine( $module, $initConfigData, $csvFilename, $row );
	}

	function textsite( $module, $initConfigData, $csvFilename, $zip ) {
		$this->textsiteCommandLine( $module, $initConfigData, $csvFilename, $zip );
	}

	function zipTextsite( $initConfigData, $csvFilename, $options ) {
		$this->zipTextsiteCommandLine( $initConfigData, $csvFilename, $options );
	}
}

trait ZipTextsiteCommandLine {
	function zipTextsiteCommandLine( $initConfigData, $csvFilename, $options ) {
		foreach ( $initConfigData as $configs ) {
			$this->loopThroughConfigsForZip( $csvFilename, $configs );
		}
	}

	function loopThroughConfigsForZip( $csvFilename, $configs ) {
		foreach ( $configs as $conf ) {
			//php text create zip demo domain.com
			$cmd = 'php text create zip ' . $csvFilename . ' ' . $conf['domain'];
			echo shell_exec( $cmd );
		}
	}
}

trait TextsiteCommandLine {
	function textsiteCommandLine( $module, $initConfigData, $csvFilename, $zip ) {
		foreach ( $initConfigData as $configs ) {
			$this->loopThroughConfigs( $module, $csvFilename, $configs, $zip );
		}
	}

	function loopThroughConfigs( $module, $csvFilename, $configs, $zip ) {
		$this->printTextsiteHead( $csvFilename );
		foreach ( $configs as $conf ) {
			$domain = $conf['domain'];

			if ( $module == 'code' || $module == 'siteall' )
				$this->textsiteCommand( 'code', $csvFilename, $domain );

			if ( $module == 'config' || $module == 'siteall' )
				$this->textsiteCommand( 'config', $csvFilename, $domain );

			if ( $module == 'htaccess' || $module == 'siteall' )
				$this->textsiteCommand( 'htaccess', $csvFilename, $domain );

			if ( $module == 'sitemap' || $module == 'siteall' )
				$this->textsiteCommand( 'sitemap', $csvFilename, $domain );

			if ( $module == 'sitemapindex' || $module == 'siteall' )
				$this->textsiteCommand( 'sitemapindex', $csvFilename, $domain );

			if ( $module == 'robots' || $module == 'siteall' )
				$this->textsiteCommand( 'robots', $csvFilename, $domain );

			if ( $module == 'theme' )
				$this->textsiteCommand( 'theme', $csvFilename, $domain );

			if ( $zip == 'zip' )
				$this->textsiteCommand( 'zip', $csvFilename, $domain );
			
		}
	}

	function textsiteCommand( $module, $csvFilename, $domain ) {
		echo "\n*** " . ucfirst( $module ) . " Creating... " . $domain. "\n";
		//$cmd = 'php text --module=' . $module . ' create site ' . $csvFilename . ' ' . $domain;
		$cmd = 'php text create ' . $module . ' ' . $csvFilename . ' ' . $domain;
		echo shell_exec( $cmd );
	}

	function printTextsiteHead( $csvFilename ) {
		echo "\n";
		echo "TextSite: " . $csvFilename . 'csv';
		echo "\n-----------------------------------------\n";
	}
}

trait TextDbCommandLine {
	function textDbCommandLine( $module, $initConfigData, $csvFilename, $row ) {
		$groups = array_keys( $initConfigData );
		
		foreach ( $groups as $iniFilename) {
			$this->TextDbPrintHead( $iniFilename );

			if ( $module == 'product' || $module == 'allTextDb' ) 
				$this->textDbCommand( 'product', $csvFilename, $iniFilename, $row );

			if ( $module == 'category' || $module == 'allTextDb' ) 
				$this->textDbCommand( 'category', $csvFilename, $iniFilename, $row );

			if ( $module == 'homepagecat' || $module == 'allTextDb' ) 
				$this->textDbCommand( 'homepagecat', $csvFilename, $iniFilename, $row );
		}
	}

	function textDbCommand( $module, $csvFilename, $iniFilename, $row ) {
		$optRow = null;
		if ( !empty( $row ) ) $optRow = '--row=' . $row;

		$cmd = 'php textdb ' . $optRow . ' create ' . $module .  ' ' . $csvFilename . ' ' . $iniFilename;
		echo "\n*** " . ucfirst( $module ) . " Creating...\n";
		echo $cmd . "\n";

		echo shell_exec( $cmd );
	}

	function TextDbPrintHead( $iniFilename ) {
		echo "\n";
		echo "Text Database: Group by " . $iniFilename . 'ini';
		echo "\n-----------------------------------------\n";
	}
}

