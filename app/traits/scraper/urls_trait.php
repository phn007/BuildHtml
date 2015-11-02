<?php
include WT_APP_PATH . 'extensions/scraper-class/_csvReader.php';

/**
 * Urls for scrape web content
 */
trait UrlData {
	use checkCsvFile;
	private $rowStart;
	private $rowEnd;
	private $data;

	function setUrlData() {
		$csvpath = $this->checkCsvFile();
		$this->data = $this->readCsvFile( $csvpath );
		$this->setRowNumber();
		return $this->setCsvData();
	}

	function setCsvData() {
		$i = 1;
		foreach ( $this->data as $value ) {
			$row[$i] = $value;
			$i++;
		}
		for ( $i = $this->rowStart; $i <= $this->rowEnd; $i++ ) {
			$output[$i] = $row[$i]; 
		}
		return $output;
	}

	function setRowNumber() {
		$arr = explode( '-', $this->params['row'] );
		if ( count( $arr ) > 1 ) {
			$rowStart = $arr[0];
			$rowEnd = $arr[1];
			if ( $rowStart > $rowEnd )
				die( 'Error: start row more than end row' );
		} else {
			$rowStart = $arr[0];
			$rowEnd = count( $this->data );
			if ( $rowStart == 0 ) $rowStart = 1;
		}
		$this->rowStart = $rowStart;
		$this->rowEnd = $rowEnd;
	}

	function readCsvFile( $path ) {
		$csv = new CSVReader();
		$csv->useHeaderAsIndex();
      	return $csv->data( $path );
	}
}

/**
 * Check Existing Csv file
 */
trait checkCsvFile {
	function checkCsvFile() {
		$filepath = $this->csvFilepath();
		return $this->checkFileExists( $filepath );
	}

	function checkFileExists( $filepath ) {
		if ( ! file_exists( $filepath ) )
			die( 'csv file not found!' );
		return $filepath;
	}

	function csvFilepath() {
		return WT_BASE_PATH . 'files/scraper/' . $this->merchantName . '/' . $this->csvFilename();
	}

	function csvFilename() {
		return $this->options['config'];
	}
}