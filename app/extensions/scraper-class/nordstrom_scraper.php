<?php

function setMerchantUrl() {
	return 'http://shop.nordstrom.com';
}

/**
* UrlFormat
*/
class UrlFormat {
	function get( $html ) {
		$ul = $html->find( 'ul[class=numbers]', 0 );
		if ( ! $ul ) return false;

		$a = $ul->find( 'a' );
		if ( count( $a ) > 0 ) $page_url = $a[0]->href;
		else $page_url = null;
		return $page_url;
	}
}

/**
* LastPageNumber
*/
class LastPageNumber {
	function get( $html ) {
		$ul = $html->find( 'ul[class=numbers]', 0 );
		if ( ! $ul ) return false;

		$a = $ul->find( 'a' );
		if ( count( $a ) > 0 ) {
			$li = $ul->find( 'li[class=last]', 0 );
			$a = $li->find( 'a' );
			$last_page_num = end( $a )->plaintext;
		} 
		else $last_page_num = 1;
		return $last_page_num;
	}
}

/**
* Set Page Url Format
*/
class DefinePageUrl {
	function set( $pageinfo, $currentPage ) {
		$url = $pageinfo['catUrl'];
		if ( $currentPage != 1 ) {
			$page = 'page=' . $currentPage;
			$url = str_replace( 'page=2', $page, $pageinfo['urlFormat'] );
		}
		return $url;	
	}
}

/**
* Parsing Product Items from webpage
*/
class ProductItems {
	function get( $html ) {
		$fashion_results = $html->find( 'div[class=fashion-results]', 0 );
		if ( !$fashion_results ) return false;

		$fashion_item = $fashion_results->find( 'div[class=fashion-item]' );
		if ( ! $fashion_item ) return false;

		foreach ( $fashion_item as $item ) {
			$title = $item->find( 'a[class=title]', 0 )->plaintext;
			$img = $item->find( 'img', 0 )->getAttribute( 'data-original' );
			$url = $item->find('a[class=title]', 0 )->href;
			$price = $item->find( 'span[class=price]', 0 )->plaintext;
			$price = $this->convertThaiToUSDRate( $price );
			$data[] = array(
				'title' => $title,
				'image' => $img,
				'url' => $url,
				'price' => $price,
			);
		}
		return $data;
	}

	function convertThaiToUSDRate( $price ) {
		$number = str_replace( 'THB ', '', $price );
		$price = $number * 0.03 ;
		return number_format( ( float )$price, 2, '.', '' ); 
	}
}

/**
 * PRODUCT DETAIL SECTION
 * =============================================================================
 */
class ProductDetail {
	function get( $html ) {
		$detail = $this->getProductDesc( $html ) . $this->getFeature( $html );
		return array(
			'detail' => $detail,
			'brand' => $this->getBrand( $html )
		);
	}

	function getProductDesc( $html ) {
		if ( $desc = $html->find( 'div[class=accordion-content] p', 0 ) ) $desc = $desc->plaintext;
		return isset( $desc ) ? '<p>' . $desc . '</p>' : null;
	}

	function getFeature( $html ) {
		if ( $feature = $html->find( 'div[class=accordion-content] ul[class=style-features]', 0 ) ) 
			$feature = $feature->innertext;
		return isset( $feature ) ? '<ul>' . $feature . '</ul>' : null;
	}

	function getBrand( $html ) {
		if ( $brand = $html->find( 'section[id=brand-title] h2', 0 ) ) $brand = $brand->plaintext;
		return isset( $brand ) ? $brand : null;
	}
}
