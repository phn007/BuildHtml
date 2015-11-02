<?php
use webtools\controller;
use webtools\libs\Helper;

class SeparatorModel {
	use CheckSeparator;
	use DisplayCategory;
	use GetSeparator;
	use WriteSeparatorToTextFile;

	function addSeparator( $merchants, $iniFilename ) {
		$result = $this->checkSeparator( $merchants );

		if ( empty( $result ) ) {
			echo "All Merchants already have Separator\n";
		} else {
			$this->showMerchants( $result );
			$this->showCategoryDetail( $result[0] );
			$separator = $this->getInputSeparator( $result[0] );
			$this->writeSeparatorToTextFile( $result[0], $separator );
			//$this->runShell( $iniFilename );
		}
	}

	function runShell( $iniFilename ) {
		$cmd = 'php textdb separator check ' . $iniFilename;
		shell_exec( $cmd );
	}

	function showMerchants( $merchants ) {
		$i = 1;
		foreach ( $merchants as $merchant ) {
			echo $i++ . '. ' . $merchant;
			echo "\n";
		}
		echo "============================================\n";
	}
}

trait WriteSeparatorToTextFile {
	function writeSeparatorToTextFile( $merchant, $separator ) {
		$path = $this->getTextFilePath();
		$data = $merchant . '|' . $separator . PHP_EOL;
		file_put_contents( $path, $data, FILE_APPEND | LOCK_EX );
	}

	function getTextFilePath() {
		return WT_BASE_PATH . 'files/separator_category.txt';
	}
} 

trait DisplayCategory {
	function showCategoryDetail( $merchant ) {
		$dbName = $this->convertMerchantToDatatbase( $merchant );
		$this->connectDB();
		$this->selectCategoryTable( $dbName );
	}

	function connectDB() {
		$this->db = new Database();
		$this->conn = $this->db->connectDatabase();
	}

	function selectCategoryTable( $dbName ) {
		if ( $this->db->selectDatabase( $this->conn, $dbName ) ) {
			$sql = "SELECT * FROM category";
			if ( ! $result = mysqli_query( $this->conn, $sql ) )
				die( "Cannot Query " . $dbName . "Database" );

			echo "*** CATEGORY DETAIL\n\n";
			while( $row = mysqli_fetch_array( $result ) ) {
				echo $row['merchant'] . '| ' . $row['category'];
				echo "\n";
			}
		}
	}

	function convertMerchantToDatatbase( $merchant ) {
		$dbName = Helper::clean_string( $merchant );
		return 'prosp_' . str_replace( '-', '_', $dbName );
	}
}

trait GetSeparator {
	function getInputSeparator( $merchant ) {
		echo "\n";
		echo "Enter separator for " . $merchant . ": ";
		$handle = fopen ( "php://stdin","r" );
		$line = fgets( $handle );
		$line = trim( $line );
		if ( empty( $line ) ) {
		    echo "ABORTING!\n";
		    exit;
		}

		if ( $line == "none" ) $line = NULL;
		return $line;
	}
}

trait CheckSeparator {
	function checkSeparator( $merchants ) {
 		$path = $this->separatorTextFilePath();
 		$contents = $this->readSeparatorTextFile( $path );
 		$merchantSeparators = $this->createArrayFromTextFileContent( $contents );
 		return $this->checkMerchantSeparator( $merchants, $merchantSeparators);
	}

	function printCheckSeparatorResult( $result ) {
		if ( empty( $result ) ) {
			echo "All Merchants already have Separator\n";
		} else {
			echo "List\n";
			echo "----\n";
			foreach ( $result as $merchant ) {
				echo $merchant;
				echo "\n";	
			}
			die();
		}
	}

	function checkMerchantSeparator( $merchants, $merchantSeparators) {
		$data = array();
		foreach ( $merchants as $merchant ) {
			if ( !array_key_exists( $merchant, $merchantSeparators ) ) {
				$data[] = $merchant;
			}
		}
		return $data;
	}

	function createArrayFromTextFileContent( $contents ) {
		foreach ( $contents as $line ) {
			$arr = explode( '|', $line );
			$data[$arr[0]] = $arr[1];
		}
		return $data;
	}	

	function readSeparatorTextFile( $path ) {
		if ( !file_exists( $path ) ) die( 'separator_category.txt does not exitst' );
		$lines = file( $path );
		return array_map( 'trim', $lines );
	}

	function separatorTextFilePath() {
		return FILES_PATH . 'separator_category.txt';
	}
}