<?php
trait CategoryPaginator {
	use PageUrl;
	use PageStatus;

	function getPagination( $params, $pageNumberList, $catType ) {
		$currentPageNumber = $this->getCurrentPageNumber( $params ); //CategoryItems Trait | BrandItems Trait
		$lastPageNumber = $this->getLastPage( $pageNumberList );
		$catKey = $this->getCatKey( $params );
		return array(
			'url' => $this->getPageUrl( $catKey, $catType, $currentPageNumber, $lastPageNumber ),
			'status' => $this->getPageStatus( $currentPageNumber, $lastPageNumber )
		);
	}

	function getCatKey( $params ) {
		if ( isset( $params[0] ) )
			return $params[0];
	}

	function getLastPage( $pageNumberList ) {
		end( $pageNumberList );
		return $lastPage = key( $pageNumberList );
	}
}

trait PageStatus {
	function getPageStatus(  $currentPageNumber, $lastPageNumber ) {
		$pageStatus = $this->initialPageStatus();
		$pageStatus = $this->firstStatus( $pageStatus, $currentPageNumber );	
		$pageStatus = $this->prevStatus( $pageStatus, $currentPageNumber );	
		$pageStatus = $this->nextStatus( $pageStatus, $currentPageNumber, $lastPageNumber );
		$pageStatus = $this->lastStatus( $pageStatus, $currentPageNumber, $lastPageNumber );
		return $pageStatus;
	}

	function firstStatus( $pageStatus, $currentPageNumber ) {
		if ( $currentPageNumber <= 1 ) $pageStatus['firstStatus'] = false;
		return $pageStatus;
	}

	function prevStatus( $pageStatus, $currentPageNumber ) {
		if ( $currentPageNumber <= 1 ) $pageStatus['prevStatus'] = false;
		return $pageStatus;
	}

	function nextStatus( $pageStatus, $currentPageNumber, $lastPageNumber ) {
		if ( $currentPageNumber >= $lastPageNumber ) $pageStatus['nextStatus'] = false;
		return $pageStatus;
	}

	function lastStatus( $pageStatus, $currentPageNumber, $lastPageNumber ) {
		if ( $currentPageNumber >= $lastPageNumber ) $pageStatus['lastStatus'] = false;
		return $pageStatus;
	}

	function initialPageStatus() {
		return array(
			'firstStatus' => true,
			'prevStatus' => true,
			'nextStatus' => true,
			'lastStatus' => true
		);
	}
}

trait PageUrl {
	function getPageUrl( $catKey, $catType, $currentPageNumber, $lastPageNumber ) {
		return array(
			'firstUrl' => $this->getFirstPageUrl( $catKey, $catType, $currentPageNumber ),
			'prevUrl' => $this->getPrevpageUrl( $catKey, $catType, $currentPageNumber ),
			'nextUrl' => $this->getNextPageUrl( $catKey, $catType, $currentPageNumber, $lastPageNumber ),
			'lastUrl' => $this->getLastUrl( $catKey, $catType, $currentPageNumber, $lastPageNumber )
		);
	}

	function getFirstPageUrl( $catKey, $catType, $currentPageNumber ) {
		return $this->createPageUrl( $catKey, $catType )  . FORMAT;
	}

	function getLastUrl( $catKey, $catType, $currentPageNumber, $lastPageNumber ) {
		return $this->createPageUrl( $catKey, $catType ) . '/' . $lastPageNumber . FORMAT;
	}

	function getNextPageUrl( $catKey, $catType, $currentPageNumber, $lastPageNumber ) {
		if ( $currentPageNumber < $lastPageNumber ) {
			$nextPageNumber = $currentPageNumber + 1;
			return $this->createPageUrl( $catKey, $catType ) . '/' . $nextPageNumber  . FORMAT;
		}
		return null;
	}

	function getPrevPageUrl( $catKey, $catType, $currentPageNumber ) {
		if ( $currentPageNumber > 1 ) {
			$prevPageNumber = $currentPageNumber - 1;
			$sub = $prevPageNumber != 1 ? '/' . $prevPageNumber : null;
			return $this->createPageUrl( $catKey, $catType ) . $sub . FORMAT;
		}
		return null;
	}

	function createPageUrl( $catKey, $catType ) {
		return $this->getCategoryLink( $catType, $catKey );
	}
}