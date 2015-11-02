<?php
trait HostnineInfo {
	function getApiUrl() {
		return 'https://cp.hostnine.com/api/';
	}

	function getApiKey( $accountName ) {
		$accounts = $this->accounts();
		if ( ! array_key_exists( $accountName, $accounts ) )
			die( 'Hostnine: account name not found' );
		return $accounts[$accountName]['api_key'];
	}

	function accounts() {
		return array(
			'maxcom' => array(
				'user' => 'maxcom',
				'password' => 'bbIX746r4q',
				'api_key' => 'Uq5plhH6UUobqhhU1bTHca5DxUC4euto4',
			),
			'ratzin' => array(
				'user' => 'mnyhhhlq',
				'password' => '1ip28UI6wm',
				'api_key' => 'M2fXDzAWrbae2kGlXAknS4vdN2RhJV93b',
			),
			'rexce' => array(
				'user' => 'nbbqasol',
				'password' => 'f0j40GC8rz',
				'api_key' => '9dG99tk8O9aepZ6PWo7uegfpEDnSdROb6'
			)
		);
	}
}