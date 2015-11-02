<?php
use webtools\libs\Helper;

trait SetupConfig {
	use ConfigDataFromCsvFile;
	use DotINIFile;
	use ConvertMerchantToDatabase;
	use ReadStatTextFile;
	use GetConfigData;
}

trait ConfigDataFromCsvFile {
	function initialConfigDataFromCsvFile( $options ) {
		$csvfile = $this->getDataFromCsvFile( $options ); //getCsvConfigData trait
		foreach ( $csvfile as $conf ) {
			if ( !empty( $conf['domain'] ) ) {
				$initConfigData[$conf['config_file']][] = $conf;
			}
		}
		return $initConfigData;
	}

	function getSiteNumber( $csvData ) {
		return count( $csvData );
	}

	function getDomains( $csvData ) {
		foreach ( $csvData as $data ) {
			$domains[] = $data['domain'];
		}
		return $domains;
	}

	function getSiteDirNames( $csvData ) {
		foreach ( $csvData as $data ) {
			$siteDirs[] = $data['site_dir'];
		}
		return $siteDirs;
	}
}

trait DotINIFile {
	private $conf;

	function initialDotINIConfigFile( $filename, $options ) {
		$this->filename = $filename . '.ini';
		$this->project = $options['config'];
		$this->readDotINIConfigFile();
	}

	function getMerchantForCalcalate( $options ) {
		$this->filename = $options['config'] . '.ini';
		$this->readDotINIConfigFile();
		foreach ( $this->getMerchants() as $merchant ) {
			$data[ $merchant ] = array(
               	'db_name' => $this->convertMerchantToDbName( $merchant )
            );
		}
		return $data;
	}

	function getMerchantForProspApi( $options ) {
		$this->filename = $options['config'] . '.ini';
		$this->readDotINIConfigFile();
		foreach ( $this->getMerchants() as $merchant ) {
			$data[ $merchant ] = array(
				'project' => $options['config'],
               	'db_name' => $this->convertMerchantToDbName( $merchant ),
               	'clear_db' => $this->getClearDatabaseStatus(),
            );
		}
		return $data;
	}

	function getMerchantData() {
        foreach ( $this->getMerchants() as $merchant ) {
            $data[ $merchant ] = array(
               'project' => $this->project,
               'db_name' => $this->convertMerchantToDbName( $merchant ),
               'network' => $this->getNetwork(),
               'clear_db' => $this->getClearDatabaseStatus(),
            );
        }
		return $data;
	}

	function readDotINIConfigFile() { 
		$this->conf = new Config_Lite( CONFIG_PATH . $this->filename ); 
	}

	function getNetwork() {
		return $this->conf->get( null, 'network' );
	}

	function getApiKey() {
		return $this->conf->get( null, 'api_key' );
	}

	function getSiteType() {
		return $this->conf->get( null, 'site_type' );
	}

	function getSiteCategory() {
		return $this->conf->get( null, 'site_category' );
	}

	function getHostname() {
		return $this->conf->get( null, 'hostname' );
	}

	function getClearDatabaseStatus() {
		return $this->conf->get( null, 'clear_database' );
	}

	function getMerchants() {
		return $this->conf->get( 'merchant' );
	}
}

trait ConvertMerchantToDatabase {
	function convertMerchantToDbName( $merchant ) {
      	$dbName = Helper::clean_string( $merchant );
      	$dbName = $this->setDatabaseNameForEachNetwork( $this->getNetwork(), $dbName );
      	return $dbName;
	}

	function setDatabaseNameForEachNetwork( $network, $dbName ) {
		if ( $network == 'viglink' )
			$dbName = 'vl_' . str_replace( '-', '_', $dbName ) . '_test';
		if ( $network == 'prosperent-api' )
      		$dbName = 'prosp_' . str_replace( '-', '_', $dbName );
      	return $dbName;
	}
}

trait GetConfigData {
	/**
	 * Set General Config Group By DomainName
	 */
	function getConfigData( $csvData ) {
		foreach ( $csvData as $siteConfig ) {
			$configs = $this->setSiteConfigGroup( $siteConfig );
			$group[$siteConfig['domain']] = $configs;
		}
		return $group;
	}
	/**
	 * Set SiteConfig Group By DomainName
	 */
	function getSiteConfigData( $csvData, $options ) {
		$statData = $this->readStatTextFile( $options );

		foreach ( $csvData as $siteConfig ) {
			$configs = $this->setSiteConfigGroup( $siteConfig );
			$configs = $this->addStatDataIntoSiteConfig( $configs, $statData );
			$group[$siteConfig['domain']] = $configs;
		}
		return $group;
	}

	function addStatDataIntoSiteConfig( $configs, $statData ) {
		if ( isset( $statData[ $configs['domain'] ] ) )
			$configs['statcounter'] = $statData[$configs['domain'] ];
		else 
			die( "StatCount Code: " . $configs['domain'] . ' not found' . "\n" );
		return $configs;
	}

	function setSiteConfigGroup( $siteConfig ) {
		foreach ( $siteConfig as $key => $value ) {
			if ( 'domain' == $key ) {
				$data['domain'] = 'http://' . $value;
			}  
		}

		//from .ini
		$data['site_type']     = $this->getSiteType();
		$data['project']       = $this->project;
		$data['network']       = $this->getNetwork();
		$data['api_key']       = $this->getApiKey();
		$data['hostname']      = $this->getHostname();
		$data['site_category'] = $this->getSiteCategory();

		//from csv
		$data['site_dir'] = $siteConfig['site_dir'];
		$data['site_name'] = $siteConfig['site_name'];
		$data['prefix_sid'] = $siteConfig['prefix_sid'];
		$data['url_format'] = $siteConfig['url_format'];
		$data['theme_name'] = $siteConfig['theme_name'];
		return $data;
	}
}

trait ReadStatTextFile {
	function readStatTextFile( $options ) {
		$statTextFilename = $options['config'] . '-stat.txt';
		$path = $this->getStatTextFilePath( $statTextFilename );

		$contents = file( $path );
		$contents = array_map( 'trim', $contents );
		foreach ( $contents as $content ) {
			if ( !empty( $content ) ) {
				$arr = explode( '|', $content );
				$domain = $arr[0];
				$scCode = $arr[1];
				$data[$domain] = $scCode;
			}
		}
		if ( empty( $data ) ) die( "Empty Statcouter code" );
		return $data;
	}

	function getStatTextFilePath( $statTextFilename ) {
		$path = FILES_PATH . 'statcounter/' . $statTextFilename;
		if ( !file_exists( $path ) ) die( 'Statcounter File: ' . $statTextFilename . ' not found' );
		return $path;
	}
}