<?php
use webtools\controller;
use webtools\libs\Helper;

include WT_APP_PATH . 'traits/getCsvConfigDataV2_trait.php';
include WT_BASE_PATH . 'libs/csvReaderV2.php';

class HostModel {
	use GetCsvConfigData;

	//php hostnine accounts modify maxcom domain.com
	/*
		Options
			--quota
			-- bandwidth
	*/
	function hostnineModifyAccounts( $csvFilename, $options ) {
		$row = isset( $options['row']) ? $options['row'] : null;
		$results = $this->getDataFromCsvFile( $csvFilename, $row ); //see, getCsvConfigData Trait

		$quota = isset( $options['quota'] ) ? '--quota=' . $options['quota'] : null;
		$bandwidth = isset( $options['bandwidth'] ) ? '--bandwidth=' . $options['bandwidth'] : null;
		$modifyOption = $quota . ' ' . $bandwidth;

		foreach ( $results as $row ) {
			$domain = $row['domain'];
			$account = $row['account'];
			
			$cmd = 'php hostnine ' . $modifyOption . ' accounts modify ' . $account . ' ' . $domain;
			echo shell_exec( $cmd );
		}
	}

	//php hostnine accounts terminate maxcom domain.com
	function hostnineTerminateAccounts( $csvFilename, $options ) {
		$row = isset( $options['row']) ? $options['row'] : null;
		$results = $this->getDataFromCsvFile( $csvFilename, $row ); //see, getCsvConfigData Trait

		foreach ( $results as $row ) {
			$domain = $row['domain'];
			$account = $row['account'];
			$cmd = 'php hostnine accounts terminate ' . $account . ' ' . $domain;
			echo shell_exec( $cmd );
		}
	}

	//php hostnine accounts create maxcom domain.com username password "location" package
	function hostnineCreateAccounts( $csvFilename, $options ) {
		$row = isset( $options['row']) ? $options['row'] : null;
		$results = $this->getDataFromCsvFile( $csvFilename, $row ); //see, getCsvConfigData Trait
		foreach ( $results as $row ) {
			$account = $row['account'];
			$domain = $row['domain'];
			$username = $row['host_user'];
			$password = $row['host_pass'];
			$location = '"' . $row['location'] . '"';
			$package = $row['package'];

			$cmd = 'php hostnine accounts create ';
			$cmd .= $account . ' ';
			$cmd .= $domain . ' ';
			$cmd .= $username . ' ';
			$cmd .= $password . ' ';
			$cmd .= $location . ' ';
			$cmd .= $package;
			echo shell_exec( $cmd );
		}
	}
}