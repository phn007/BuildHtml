<?php
function setMerchantUrl() {
	return 'http://www.zappos.com';
}

/**
* UrlFormat
*/
class UrlFormat {
	function get( $html ) {
		$div = $html->find( 'div[class=pagination]', 0 );
		if ( ! $div ) return false;

		$a = $div->find( 'a' );
		$num_link = count( $a );
		$page_url = $num_link > 0 ? $a[0]->href : false;
		return $page_url;
	}
}

/**
* LastPageNumber
*/
class LastPageNumber {
	function get( $html ) {
		$div = $html->find( 'div[class=pagination]', 0 );
		if ( ! $div ) return false;

		$a = $div->find( 'a' );
		if ( count( $a ) > 0 ) {
			#last page number
			$end = end( $a );
			$end = $end->plaintext;
			$end = (int)$end;

			if ( $end == 0 ) {
				$last_arr_num  = count( $a ) - 2;
				$last_page_num = (int)$a[ $last_arr_num ]->plaintext;
			}
		} else $last_page_num = 1;
		return $last_page_num;
	}
}

/**
* Set Page Url Format
*/
class DefinePageUrl {
	function set( $pageinfo, $currentPage ) {
		$url = $pageinfo['catUrl'];
		$urlFormat = $pageinfo['urlFormat'];

		if ( $currentPage != 1 ) {
			$num = $currentPage - 1;
         	$url = setMerchantUrl() . str_replace( 'p=1', 'p='. $num, $urlFormat );
		}
		return $url;	
	}
}

/**
* Parsing Product Items from webpage
*/
class ProductItems {
	function get( $html ) {

		$div = $html->find( 'div[id=searchResults]', 0 );
		if ( ! $div ) return false;

		$products = $div->find( 'a' );
		if ( ! $products ) return false;

		foreach ( $products as $item ) {
			$title = $item->find( 'span[class=productName]', 0 )->plaintext;
			$img   = $item->find( 'img', 0 )->src;
			$url   = $item->href;
			$price = $item->find( 'span[class=price]', 0 )->plaintext;
			$brand = $item->find( 'span[class=brandName]', 0 )->plaintext;

			$data[] = array(
				'title' => $title,
				'image' => $img,
				'url' => $url,
				'price' => $price,
				'brand' => $brand
			);
		}
		return $data;
	}
}

/**
 * PRODUCT DETAIL SECTION
 * =============================================================================
 */
class ProductDetail {
	function get( $html ) {
		
	}
}