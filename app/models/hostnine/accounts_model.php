<?php
use webtools\controller;
use webtools\libs\Helper;

include WT_BASE_PATH . 'libs/TablePrinter.php'; 
//include WT_BASE_PATH . 'libs/csvReader.php';
include WT_BASE_PATH . 'libs/csvReaderV2.php';
include WT_APP_PATH . 'traits/hostnine/hostnineInfo_trait.php';
include WT_APP_PATH . 'traits/hostnine/resellerCentralQuery_trait.php';
include WT_APP_PATH . 'traits/getCsvConfigDataV2_trait.php';

class AccountsModel extends Controller {
	use HostnineInfo;
	use ResellerCentralQuery;

	//get accounts
	use GetAccounts;
	use SortResults;

	//termainate account
	use TerminateAccount;
	use GetCsvConfigData;

	use CreateAccount;
	use ModifyAccount;

	/**
	 * Retrieve a list of accounts.
	 */
	function get( $params, $options ) {
		$account = $params['account'];
		$domain = $params['domain'];
		$apiKey = $this->getApiKey( $account ); //see, HostnineInfo Trait
		$accounts = $this->getAccounts( $domain, $apiKey );	//see, GetAccounts Trait
		$accounts = $this->sortResults( $accounts, $options ); //see, SortResults Trait
		$this->displayResults( $accounts ); //see, SortResults Trait
	}

	/**
	 * Setup a new account.
	 */
	function create( $params , $options ) {
		$this->createAccountFromInputParams( $params );
	}

	/**
	 * Terminate and remove an account's hosting service that is setup within your Reseller Central.
	 */
	function terminate( $params, $options ) {
		$this->terminateAccountFromInputParams( $params );	
	}

	/**
	 * Modify the hosting options of an account within your Reseller Central.
	 */
	function modify( $params, $options ) {
		$this->modifyAccountFromInputParams( $params, $options );
	}
}//Model Class


trait ModifyAccount {
	function modifyAccountFromInputParams( $params, $options ) {
		$apiKey = $this->getApiKey( trim( $params['account'] ) );
		$params = $this->getModifyParams( $apiKey, $params, $options );
		$modifyAccount = $this->resellercentralQuery( 'modifyAccount', $params ); //see, resellerCentralQuery_trait
		$this->printModifyResult( $params['domain'], $modifyAccount );
	}

	function getModifyParams( $apiKey, $params, $options ) {
		$arr['api_key'] = $apiKey;
		$arr['domain'] = trim( $params['domain'] );

		if ( isset( $options['quota'] ) )
			$arr['quota'] = $options['quota'];

		if ( isset( $options['bandwidth'] ) )
			$arr['bandwidth'] = $options['bandwidth'];
		return $arr;
	}

	function printModifyResult( $domain, $modifyAccount ) {
		if ( $modifyAccount['success'] == 'true')
			echo $modifyAccount['result'] . "\n";
		else 
			echo 'Error creating account, result: ' . $modifyAccount['result'] . "\n";
	}
}

/**
 * CREATE ACCOUNT TRAIT SECTION
 * =======================================================================================================================
 */
trait CreateAccount {
	function createAccountFromInputParams( $params ) {
		$apiKey = $this->getApiKey( trim( $params['account'] ) );
		$this->createAccount( $apiKey, $params );
	}

	function createAccount( $apiKey, $params ) {
		$params = $this->getCreateAccountParams( $apiKey, $params );
		$newAccount = $this->resellercentralQuery( 'createAccount', $params ); //see, resellerCentralQuery_trait
		$this->printCreateResult( $params['domain'], $newAccount );
	}

	function getCreateAccountParams( $apiKey, $params ) {
		return array(
			'api_key' => $apiKey, 
			'domain' => trim( $params['domain'] ), 
			'username' => trim( $params['username'] ),
			'password' => trim( $params['password'] ), 
			'location' => trim( $params['location'] ),
			'package' => trim( $params['package'] )
		);
	}

	function printCreateResult( $domain, $newAccount ) {
		if ( $newAccount['success'] == 'true') {
			$dom = $newAccount['account']['domain'];
			$ip = $newAccount['account']['ip'];
			$server = $newAccount['account']['servername'];
			echo $dom . ', ' . $ip . ', ' . $server . ': Successfully created account.' . "\n";
		} else  {
			echo $domain . ': Error creating account, result: ' . $newAccount['result'] . "\n";
		}
	}
}

/**
 * TERMINATE ACCOUNT TRAIT SECTION
 * =======================================================================================================================
 */
trait TerminateAccount {
	function terminateAccountFromInputParams( $params ) {
		$apiKey = $this->getApiKey( $params['account'] );
		$domain = $params['domain'];
		$this->terminateAccount( $apiKey, $domain );
	}

	function terminateAccount( $apiKey, $domain ) {
		$params = $this->getTerminateParams( $apiKey, $domain );
		$terminate = $this->resellercentralQuery( 'terminateAccount', $params ); //see, resellerCentralQuery_trait
		$this->printTerminateResult( $terminate );
	}

	function printTerminateResult( $terminate ) {
		if ( $terminate['success'] != 'true' ) die('Can not be remove ' . $domain . ' domain' );
		echo $terminate['result'];
		echo "\n";	
	}

	function getTerminateParams( $apiKey, $domain ) {
		return array(
			'api_key' => $apiKey,
			'domain' => $domain
		);
	}
}

trait GetAccounts {
	function getAccounts( $domain, $apiKey ) {
		$params = $domain == 'all' ? $this->allAccountParams( $apiKey ) : $this->specificDomainParams( $apiKey, $domain );
		$accounts = $this->resellercentralQuery( 'getAccounts', $params ); //see, resellerCentralQuery_trait
		$this->checkResult( $accounts );
		return $accounts;
	}

	function allAccountParams( $apiKey ) {
		return $params = array( 'api_key' => $apiKey );
	}

	function specificDomainParams( $apiKey, $domain ) {
		return array(
			'api_key' => $apiKey,
			'filters' => '%search:' . $domain . '%status:1',
		);
	}

	function checkResult( $accounts ) {
		if ( $accounts['accounts'] == 0 ) die( 'Domain Not Found' );
	}
}

trait SortResults {
	function sortResults( $accounts, $options ) {
		if ( !array_key_exists( 'sort', $options ) ) 
			return $accounts['sql'];

		if ( array_key_exists( 'descending', $options ) ) 
			return $this->sortDescending( $accounts['sql'], $options['sort'] );

		if ( array_key_exists( 'ascending', $options ) )
			return $this->sortAscending( $accounts['sql'], $options['sort'] );
	}

	function sortDescending( $accounts, $sort ) {
		$this->sort = $sort;
		usort( $accounts, function( $a, $b ) {
			if( $a[$this->sort] == $b[$this->sort] ) return 0;
			return $a[$this->sort] < $b[$this->sort] ? 1: -1;
		});
		return $accounts;
	}

	function sortAscending( $accounts, $sort ) {
		$this->sort = $sort;
		usort( $accounts, function( $a, $b ) {
			if( $a[$this->sort] == $b[$this->sort] ) return 0;
			return $a[$this->sort] > $b[$this->sort] ? 1: -1;
		});
		return $accounts;
	}

	function displayResults( $accounts ) {
		$i = 1;
		$p = new TablePrinter(
			['Number', 
			 'Domain', 
			 'User', 
			 'IP Address', 
			 'Location', 
			 'Quota', 
			 'Bandwidth', 
			 'Total Quota', 
			 'Total BW', 
			 'Status' ]
		);
		foreach ( $accounts as $acc ) {
			$p->addRow( 
				$i, 
				$acc['domain'], 
				$acc['username'], 
				$acc['ip'], 
				$acc['location'], 
				$acc['rquota'], 
				$acc['rbandwidth'], 
				$acc['rtotal_disk'], 
				$acc['rtotal_bw'], 
				$acc['status'] 
			);
			$i++;
		}
		$p->output();
	}
}
