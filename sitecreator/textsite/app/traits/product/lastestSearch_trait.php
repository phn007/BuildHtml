<?php
trait LastestSearch {
	function setLastestSearch() {
		$spinCom = $this->component( 'textspinner' );
		$wordlib_path = TEXTSPIN_PATH . 'WordLibrary.csv';
		$text_path = TEXTSPIN_PATH . 'spam.txt';

		$text = file( $text_path );
		shuffle( $text );

		foreach ( $text as $line ) {
			$data = $spinCom->readWordLibrary( $wordlib_path );
			$data = $spinCom->createSpinFormatType( $data );
			$result[] = trim( $spinCom->replaceTextSpin( $data, $line ) );
		}
		$output = strtolower( implode( ',', $result ) );
		return str_replace( '#keyword#', $this->productKey, $output );
	}
}

