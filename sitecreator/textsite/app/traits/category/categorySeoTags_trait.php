<?php
trait CategorySeoTags {
	function getCategorySeoTags( $menuUrl, $catName, $catType, $params ) {
		$currentPageNumber = $this->getCurrentPageNumber( $params ); //CategoryItems Trait | BrandItems Trait
		$tags = $this->setSeoTags( $menuUrl, $catName, $catType, $currentPageNumber );
		$tagCom = $this->component( 'seoTags' );
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
		if ( $currentPageNumber == 1 ) return $catName . ' | ' . $catType;
		else return $catName . ' | ' . $catType . ' - Page ' . $currentPageNumber;
	}

	function getLinkNext( $menuUrl ) {
		if ( !empty( $menuUrl['nextUrl'] ) ) return $menuUrl['nextUrl'];
	}

	function getLinkPrev( $menuUrl ) {
		if ( !empty( $menuUrl['prevUrl'] ) ) return $menuUrl['prevUrl'];
	}
}
