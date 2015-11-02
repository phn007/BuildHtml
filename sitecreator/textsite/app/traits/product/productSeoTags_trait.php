<?php
trait ProductSeoTags {
	function getProductSeoTags() {
		$tagCom = $this->component( 'seoTags' );
		return $tagCom->createSeoTags( $this->setSeoTags() );
	}
	
	function setSeoTags() {
		$tags['title'] = $this->spinContent['title1'];
		$tags['keywords'] = $this->spinContent['title1'];
		$tags['description'] = trim( strip_tags( $this->spinContent['ad1'] ) );
		$tags['author'] = AUTHOR;
		$tags['linkCanonical'] = $this->productDetail['permalink'];

		$linkNext = $this->getLinkNext();
		if ( isset( $linkNext ) ) {
			$tags['linkNext'] = $linkNext;
		}

		$linkPrev = $this->getLinkPrev();
		if ( isset( $linkPrev ) ) {
			$tags['linkPrev'] = $this->getLinkPrev();
		}
		return $tags;
	}

	function getLinkNext() {
		if ( !empty( $this->menuUrl['nextUrl'] ) )
			return $this->menuUrl['nextUrl'];
	}

	function getLinkPrev() {
		if ( !empty( $this->menuUrl['prevUrl'] ) )
			return $this->menuUrl['prevUrl'];
	}
}
