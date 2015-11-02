<?php 
/**
* Text spinner
*/
class TextSpinnerComponent {

	//Excute Function
	function spinText( $wordlib_path, $textpath ) {
		$data = $this->readWordLibrary( $wordlib_path );
		$data = $this->createSpinFormatType( $data );
		$text = $this->randomTextContent( $textpath );
		$text = $this->replaceTextSpin( $data, $text );
		return $text;
	}

	function replaceTextSpin( $data, $text ) {
		$keys = array_keys( $data );
		$values = array_values( $data );
		$text = str_replace( $keys, $values, $text );
		return $this->runTextSpinner( $text );
	}

	function createSpinFormatType( $array ) {
		foreach ( $array as $key => $value ) {
			$key = strtolower( $key );
			shuffle( $value );

			$string = trim( $value[0] );
			$key = '[' . $key . ']';
			$data[ $key ] = $string;
		}
		return $data;
	}

	function randomTextContent( $file ) {
		$text = file( $file );
		shuffle($text);
		return $text[0];
	}

	function readWordLibrary( $path ) {
		if ( ( $handle = fopen( $path, 'r' ) ) == FALSE ) {
			echo "WordLibrary File Not Found!!!";
			exit( 0 );
		}

		//อ่านข้อมูลตามแนวนอน ( row )
		$row = 0;
		while ( ( $csv = fgetcsv( $handle, 1000, ",") ) !== FALSE ) {
			//นับจำนวนคอลัมน์ในแต่ละแถว
			$iCols = count( $csv );

			//ลูปผ่านจำนวนคอลัมน์เพื่อกำหนด Title
			for ( $i = 0; $i <= $iCols; $i++ ) {
				if ( $row == 0 ) {
					if ( isset( $csv[$i]) )
						$title[ $i ] = $csv[$i];
				}
			}//for

			//วนลูปเพื่อสร้าง array โดยใช้ Title เป็น array key
			for ( $i = 0; $i <= $iCols; $i++ ) {
				if ( $row > 0 ) {
					if ( isset( $title[ $i ] ) && isset( $csv[$i]) ) {
						if ( !empty( $csv[$i] ) )
							$data[ $title[$i] ][$row] = $csv[$i];
					}
				}
			}
			//เพิ่มจำนวนรอบของแถว
			$row++;
		}
		return $data;
	}

	function runTextSpinner( $content ) {
		$returnArray = array();
		$pattern = "/\{[^\{]+?\}/ixsm";
		preg_match_all($pattern, $content, $returnArray, PREG_SET_ORDER);

		foreach ( $returnArray as $return ) {
			$code = $return[0];
			$str = str_replace( "{", "", $code );
			$str = str_replace( "}", "", $str );
			$tmp = explode( "|", $str );
			$c = count( $tmp );
			$rand = rand( 0, ($c-1) );
			$word = $tmp[$rand];
			$content = str_replace( $code, $word, $content );
		}

		$pos = strpos( $content, "{" );
		if ( $pos === false )
			return $content;
		else
			return $this->runTextSpinner($content);
	}
}//CLASS
