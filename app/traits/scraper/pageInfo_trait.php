<?php
trait PageInfo {
	use PageNumber;

	function setPageInfo() {
		$path = $this->setPageInfoPath();

		if ( ! file_exists( $path ) ) {
			return $this->getPageInfoFromWebPage();
		} else {
			return $this->getPageInfoFromLocalFile();
		}
	}

	function getPageInfoFromWebPage() {
		$params = $this->setPageInfoParams();
		$curl = $this->component( 'curl' );
		$output = $curl->getRequest( $params );
		$pageinfo = $this->scraper->getPageInfo( $output['content'] ); //app/extensions/_scraper.php
		$pageinfo['catUrl'] = $this->item['url'];
		$this->savePageInfo( $pageinfo );
		return $pageinfo;
	}


	function setPageInfoParams() {
		return array(
			'url' => $this->item['url'],
			'returnTransfer' => 1,
			'timeout' => 60,
			'followLocation' => 1,
			'userAgent' => $this->setUserAgent(),
			'httpHeader' => $this->setHeader(),
			'referer' => $this->scraper->merchantUrl,
		);
	}

	function getPageInfoFromLocalFile() {
		$pageinfo = file_get_contents( $this->setPageInfoPath() );
		$pageinfo = unserialize( $pageinfo );

		if ( $pageinfo['catUrl'] != $this->item['url'] ) {
			$pageinfo = $this->getPageInfoFromWebPage();
		}
			
		return $pageinfo;
	}

	function savePageInfo( $pageinfo ) {
		$pageinfo = serialize( $pageinfo );
   		file_put_contents( $this->setPageInfoPath(), $pageinfo );
	}

	function setPageInfoPath() {
		return WT_BASE_PATH . 'files/scraper/' . $this->merchantName . '/pageinfo.txt';
	}
}


trait PageNumber {
	private $pageStart;
	private $pageEnd;

	function inputPageNumber() {
		$arr = explode( '-', $this->params['page'] );
		if ( count( $arr ) > 1 ) {
			$pageStart = $arr[0];
			$pageEnd = $arr[1];
			if ( $pageStart > $pageEnd )
				die( 'Error: start page more than end page' );
		} else {
			$pageStart = $arr[0];
			$pageEnd = 0;
		}
		$this->pageStart = $pageStart;
		$this->pageEnd = $pageEnd;
	}

	function setStartPageNumber( $pageinfo ) {
		if ( $this->pageStart == 0 )
			$this->pageStart = 1;
		if ( $this->pageStart > $pageinfo['lastPage'] )
			$this->pageStart = 1;
	}

	function setLastPageNumber( $pageinfo ) {
		if ( $this->pageEnd == 0 ) 
        	$this->pageEnd = $pageinfo['lastPage'];
        
        if ( $this->pageEnd > $pageinfo['lastPage'] )
        	$this->pageEnd = $pageinfo['lastPage'];
	}
}
