<?php
class Helper {
	use ImageSize;

	public static function make_dir( $path ) {
		if ( ! file_exists( $path ) ) {
			mkdir( $path, 0777, true );
		}
	}

	public static function clean_string( $string ) {
		$string = preg_replace( "`\[.*\]`U", "", $string );
		$string = preg_replace( '`&(amp;)?#?[a-z0-9]+;`i', '-', $string );
		$string = str_replace( '%', '-percent', $string );
		$string = htmlentities( $string, ENT_COMPAT, 'utf-8' );
		$string = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i", "\\1", $string );
		$string = preg_replace( array( "`[^a-z0-9ก-๙เ-า]`i", "`[-]+`") , " ", $string );
		$string = strtolower( trim( $string, '-') );
		$string = strtolower( trim( $string ) );
		$string = preg_replace( "/(.*)\/(\w{2})\/(\w{2})(\/.*|$)/", '$1/$3$4', $string );
		$string = str_replace( " ", "-", $string );
		$find   = array( '---', '--' );
		$string = str_replace( $find, '-', $string );
		return $string;
	}
	
	public static function limit_words( $string, $word_limit ) {
		$words = explode( " ", $string );
		return implode( " ", array_splice( $words, 0, $word_limit ) );
	}

	public static function image_size( $img_url, $img_size ) {
		if ( 'prosperent-api' == NETWORK ) return self::prosperentApiImage( $img_url, $img_size );
		if ( 'viglink' == NETWORK ) return img_url;
	}

	public static function showImage( $imgUrl, $imgSize=null, $alt ) {
		if ( 'prosperent-api' == NETWORK )
			return self::prosperentApiImgTag( $imgUrl, $imgSize, $alt ); //ImageSize Trait
		if ( 'viglink' == NETWORK )
			return self::viglinkImgTag( $imgUrl, $imgSize, $alt ); //ImageSize Trait
	}

	public static function getSearchKey( $keyword ) {
		$arr = explode( ' ', $keyword );
		$count = count( $arr );
		$start = $count - 3;
		if ( $start < 0 ) $start = 0;
		$arr = array_slice( $arr, $start, $count );
		return implode( ' ', $arr );
	}


}




/**
 * TRAIT SECTION
 * =======================================================================================
 */
trait ImageSize {
	static function prosperentApiImage( $img_url, $img_size ) {
		$img = explode( '/', $img_url );//img_size ( 75x75, 125x125, 250x250, 500x500 )
		$img[4] = $img_size;
		return implode( '/', $img );
	}

	static function viglinkImgTag( $imgUrl, $imgSize, $alt ) {
		if ( !empty( $imgSize ) ) {
			$arr = explode( 'x', $imgSize );
			$width = $arr[0]; $height = $arr[1];
			return '<img src="' . BLANK_IMG . '" data-echo="' . $imgUrl . '" width="' . $width . '" height="' . $height . '" alt="' . $alt . '">';
		}
		return '<img src="' . BLANK_IMG . '" data-echo="' . $imgUrl . '" alt="' . $alt . '">';
	}

	static function prosperentApiImgTag( $imgUrl, $imgSize, $alt ) {
		if ( !empty( $imgSize ) ) {
			$img = explode( '/', $imgUrl );//img_size ( 75x75, 125x125, 250x250, 500x500 )
			$img[4] = $imgSize;
			$imgUrl = implode( '/', $img );
			return '<img src="' . BLANK_IMG .'" data-echo="' . $imgUrl . '" alt="' . $alt . '">';
		}
		return '<img src="' . BLANK_IMG .'" data-echo="' . $imgUrl . '" alt="' . $alt . '">';
	}
}