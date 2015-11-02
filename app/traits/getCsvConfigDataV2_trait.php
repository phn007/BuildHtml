<?php
trait GetCsvConfigData {
	function getDataFromCsvFile( $csvFilename, $row ) {
		$filename = $this->getFilename( $csvFilename );
		$path = $this->getCsvPath( $filename );
		$csv = new CSVReader();
		$csv->useHeaderAsIndex();
		$this->setRow( $csv, $row );
		return $csv->getData( $path );
	}

	function setRow( $csv, $row ) {
		if ( !empty( $row ) ) {
			$start = $this->getStartRow( $row );
			$limit = $this->getLimitRow( $row );
			if ( empty( $limit ) ) $limit = $start;
			if ( $start > $limit ) die( 'Start number must be less than limit number');
			$csv->offset = $start - 1;
			$csv->limit = $limit -1 ;
		}
	}

	function getStartRow( $row ) {
		$arr = explode( '-', $row );
		return $arr[0];
	}

	function getLimitRow( $row ) {
		$arr = explode( '-', $row );
		if ( isset( $arr[1] ) )
			return $arr[1];
	}

	function getFilename( $csvFilename ) {
		return $csvFilename . '.csv';
	}

	function getCsvPath( $filename ) {
		$path = WT_BASE_PATH . 'configs/' . $filename;
		if ( !file_exists( $path ) ) die( $filename . ' not found' );
		return $path; 
	}
}