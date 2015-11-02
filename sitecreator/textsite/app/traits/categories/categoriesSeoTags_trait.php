<?php
trait CategoriesSeoTags {
	function getCategoriesSeoTags( $menuUrl, $catName, $catType, $params ) {
		$currentPageNumber = $this->inputPageNumber( $params ); ( $params ); //Categories Trait
		$tagCom = $this->component( 'seoTags' );
		$tags = $this->setSeoTags( $menuUrl, $catName, $catType, $currentPageNumber );
		return $tagCom->createSeoTags( $tags );
	}
	
	function setSeoTags( $menuUrl, $catName, $catType, $currentPageNumber ) {
		$tags['title'] = $this->getTitle( $catName, $catType, $currentPageNumber );
		$tags['author'] = AUTHOR;

		$linkNext = $this->getLinkNext( $menuUrl );
		if ( isset( $linkNext ) ) $tags['linkNext'] = $linkNext;

		$linkPrev = $this->getLinkPrev( $menuUrl );
		if ( isset( $linkPrev ) ) $tags['linkPrev'] = $linkPrev;

		if ( $currentPageNumber == 1 ) $tags['linkCanonical'] = $menuUrl['firstUrl'];
		return $tags;
	}

	function getTitle( $catName, $catType, $currentPageNumber ) {
		if ( $currentPageNumber == 1 ) return $catType;
		else return $catType . ' - Page ' . $currentPageNumber;
	}

	function getLinkNext( $menuUrl ) {
		if ( !empty( $menuUrl['nextUrl'] ) ) return $menuUrl['nextUrl'];
	}

	function getLinkPrev( $menuUrl ) {
		if ( !empty( $menuUrl['prevUrl'] ) ) return $menuUrl['prevUrl'];
	}
}
