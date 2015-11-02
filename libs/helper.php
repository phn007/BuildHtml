<?php
namespace webtools\libs;

class Helper {

	public static function make_dir( $path ) {
		if ( ! file_exists( $path ) ) {
			mkdir( $path, 0777, true );
		}
	}
	
	public static function deleteArrayByValue( $delVal, $array )
	{
		if ( ( $key = array_search( $delVal, $array ) ) !== false ) 
		{
    		unset( $array[ $key ] );
		}
		return $array;
	}


   // public static function getCategory( $merchant, $category )
   // {
   //    //ที่อยู่ของไฟล์
   //    $path =  FILES_PATH . 'separator_category.txt';
   //
   //    //ตรวจสอบว่ามีไฟล์อยู่หรือเปล่า
   //    if ( file_exists( $path ) )
   //    {
   //       //อ่านไฟล์ขึ้นมา
   //       $files = file( $path );
   //       $files = array_map( 'trim', $files );
   //
   //       //แยกข้อมูลแล้วเก็บไว้ในอะเรย์
   //       foreach ( $files as $file )
   //       {
   //          $arr = explode( '|', $file );
   //          $separator[$arr[0]] = $arr[1];
   //       }
   //
   //       //ตรวจสอบว่ามีข้อมูลของ merchant ที่ส่งเข้ามาหรือเปล่า
   //       if ( array_key_exists( $merchant, $separator ) )
   //       {
   //          $sep = $separator[ $merchant ];
   //
   //          //แยก category
   //
   //          if ( ! empty( $sep ) )
   //          {
   //             $cats = explode( $sep, $category );
   //
   //             //ลบ array ที่เป็นค่าว่างออก
   //             $cats = array_filter( $cats );
   //
   //             //ดึงเอาชื่อ category ตัวสุดท้ายไปใช้งาน
   //             $cat_name = end( $cats );
   //
   //          }
   //          else
   //          {
   //             $cat_name = $category;
   //          }
   //          return $cat_name;
   //       }
   //       else
   //       {
   //          echo $merchant . ': There is no separator';
   //          die();
   //       }
   //    }
   //    else
   //    {
   //       die( $path . ': File not found!!!' );
   //    }
   // }




   public static function get_input_data( $fname, $num )
   {
      if ( ! empty ( $fname[ $num ] ) )
      {
         //เก็บชื่อ file ไว้ในตัวแปร
         $file = $fname[ $num ];
      }
      else
      {
         echo "Enter incorrect number value -> " . $num;
         die();
      }
      return $file;
   }


   public static function clean_string( $string )
   {
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
}
