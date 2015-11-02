<?php
use webtools\libs\Helper;

trait SeparateCategoryForProsperentNetwork {
	function getSeparatedCategory( $merchant, $category ) {
		/*
		$path =  FILES_PATH . 'separator_category.txt';
		$this->checkSeparatorCategoryFileExist( $path );
		$files = $this->readSeparatorCategoryFile( $path );
		$separators = $this->parseSeparatorData( $files );
		
		$this->checkMerchantSeparatorExist( $merchant, $separators );
		$separator = $separators[ $merchant ];
		*/
		$separator = '>';
		$catName = $this->separateCategory( $separator, $category );

		//แยก category
		/*if ( ! empty( $separator ) )
			$catName = $this->separateCategory( $separator, $category );
		else
			$catName = $category;
		*/
		return $catName;
	}

	function checkSeparatorCategoryFileExist( $path ) {
		if ( ! file_exists( $path ) )
			die( 'separator_category.txt: ' . $path . ': File not found!!!' );
	}

	function readSeparatorCategoryFile( $path ) {
		//อ่านไฟล์ขึ้นมา
		$files = file( $path );
		$files = array_map( 'trim', $files );
		return $files;
	}

	function parseSeparatorData( $files ) {
		//แยกข้อมูลแล้วเก็บไว้ในอะเรย์
		foreach ( $files as $file ) {
			$arr = explode( '|', $file );
			$separators[$arr[0]] = $arr[1];
		}
		return $separators;
	}

	function checkMerchantSeparatorExist( $merchant, $separators ) {
		echo $merchant;
		echo "\n";
		//ตรวจสอบว่ามีข้อมูลของ merchant ที่ส่งเข้ามาหรือเปล่า
		if ( ! array_key_exists( $merchant, $separators ) )
			die( $merchant . ': TEST TEST TEST There is no separator' );
	}

	function separateCategory( $separator, $category ) {
		$cats = explode( $separator, $category );
		//ลบ array ที่เป็นค่าว่างออก
		$cats = array_filter( $cats );
		//ดึงเอาชื่อ category ตัวสุดท้ายไปใช้งาน
		$catName = end( $cats );
		return $catName;
	}
}