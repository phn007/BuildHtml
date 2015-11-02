<?php
use webtools\controller;
use webtools\libs\Helper;
include WT_APP_PATH . 'traits/textdb/mysqldb_trait.php';
include WT_BASE_PATH . 'libs/TablePrinter.php'; 

class CalculateDomainNumberModel extends Controller {
	use MySQLDatabase;
	use CalculateByProductNumber;
	use CalculateByDomainNumber;

	function calcByProducts( $merchantData, $productsPerDomain ) {
		$this->byProducts( $merchantData, $productsPerDomain );
	}

	function calcByDomains( $merchantData, $domainNumber ) {
		$this->byDomains( $merchantData, $domainNumber );
	}

	function aaa( $merchantData ) {
		$this->initialMysqlDatabase(); //MySQLDatabase Trait
		return $this->countTotalProducts( $merchantData ); //MySQLDatabase Trait
	}

	function printResult( $data, $result, $type ) {
		$p = new TablePrinter( [ 'No.', 'Merchant', 'Product Number' ] );
		$i = 1;
		foreach ( $data['merchantProductNumber'] as $merchant => $number ) {
			$p->addRow( $i, $merchant,  $number );
			$i++;
		}
		$p->output();
		echo "\n";
		echo 'Total Products: ' . $data['totalProducts'];
		echo "\n";

		if ( 'byProducts' == $type ) echo 'Domain Number: ' . $result;
		if ( 'byDomains' == $type ) echo 'Product Number Per Domain: ' . $result;
	}
	
}

trait CalculateByDomainNumber {
	function byDomains( $merchantData, $domainNumber ) {
		$data = $this->aaa( $merchantData );
		$productNumber = $this->productNumber( $data, $domainNumber );
		$this->printResult( $data, $productNumber, 'byDomains' );
	}

	function productNumber( $data, $domainNumber ) {
		return ceil( $data['totalProducts'] / $domainNumber );
	}
}

trait CalculateByProductNumber {
	function byProducts( $merchantData, $productsPerDomain ) {
		$data = $this->aaa( $merchantData );
		$domainNumber = $this->domainNumber( $data, $productsPerDomain );
		$this->printResult( $data, $domainNumber, 'byProducts' );
	}

	function domainNumber( $data, $productsPerDomain ) {
		return ceil( $data['totalProducts'] / $productsPerDomain );
	}
}