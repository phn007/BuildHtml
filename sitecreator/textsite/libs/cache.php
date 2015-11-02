<?php

class Cache
{
   function get( $path, $name, $cache_time = false )
	{
      $name = md5( $name ) . '.txt';
		$path = $path . '/' . $name;

		$curr_time  = time();
		$check_time = $curr_time - $cache_time;

		if ( file_exists( $path ) )
		{
			$file_time  = filemtime( $path );

			if ( ! $cache_time || $check_time < $file_time )
			{
				//Get Cache
				$string = file_get_contents( $path );
				$cache  = unserialize( $string );
			}
			else
			{
				$cache = null;
            	unlink( $path );
			}
		}
		else
		{
			$cache = null;
		}

      return $cache;

   }//function

   function set( $path, $name, $data )
	{
	   if ( ! file_exists( $path ) )
		{
	      mkdir( $path, 0777, true );
         //chmod( $path, 0777 );
      }

      $name  = md5( $name ) . '.txt';
      $path  = $path . '/' . $name;
      $cache = serialize( $data );
      file_put_contents( $path, $cache );

   }



   /**
    * Set Cache แบบเก็บข้อมูลรวมอยู่ในไฟล์เดียวกัน
    * -------------------------------------------------------------------------
   */
   function set_cache( $path, $filename, $data )
   {
      if ( ! file_exists( $path ) )
      {
         mkdir( $path, 0777, true );
         //chmod( $path, 0777 );
      }

      $cache_path = $path . '/' . $filename . '.txt';

      $this->put_serialize_text( $cache_path , $data );
   }


   /**
    * Get Cache แบบที่ข้อมูลอยู่ในไฟล์เดียวกัน
    * -------------------------------------------------------------------------
   */
   function get_cache( $path, $cache_file, $cache_key )
   {
      //ที่อยู่ของ Cache ไฟล์
      $cache_path = $path . '/' . $cache_file . '.txt';

      if ( file_exists( $cache_path ) ) //ถ้ามีไฟล์อยู่ให้ดึงข้อมูลขึ้นมา
      {
         $str = file_get_contents( $cache_path );
         $str = unserialize( $str );

         //ถ้ามี cache_key อยู่ในอะเรย์ ให้ดึงข้อมูลใน cache_key ขึ้นมา
         if ( array_key_exists( $cache_key, $str ) )
         {
            $cache = $str[ $cache_key ];
         }
         //ถ้าไม่มี cache_key อยู่ในอะเรย์  ให้ return ค่า NULL กลับไป
         else
         {
            $cache = NULL;
         }
      }
      else //ถ้าไม่มีไฟล์อยู่ให้ return ค่า Null กลับไป
      {
         $cache = NULL;
      }

      return $cache;
   }



   /**
    * Save Serialize Text File แบบ Append
    * -------------------------------------------------------------------------
   */
   function put_serialize_text( $path, $data )
   {
      if ( file_exists( $path ) )
      {
         //1. อ่านไฟล์ขึ้นมา
         $str = file_get_contents( $path );

         //2. unserialize array เก่า
         $str = unserialize( $str );

         //2.2 ถ้ามี key ของอะเรย์อยู่แล้วไม่ต้อง save cache

         //ดึงอะเรย์คีย์ออกมาจาก $data
         $keys = array_keys( $data );
         $key = $keys[0];

         //ตรวจสอบว่ามี key อยู่ใน $str ( array  )แล้วหรือเปล่า
         if ( array_key_exists( $key, $str ) )
         {
            return false;
         }

         //3. เพิ่มค่า array ใหม่ต่อท้ายเข้าไปใน array เก่า
         foreach ( $data as $key => $val )
         {
            $str[$key] = $val;
         }

         //4. serialize array กลับเข้าไปใหม่อีกครั้ง
         $str = serialize( $str );

         //5. save
         file_put_contents( $path, $str );
      }
      else
      {
         $str = serialize( $data );
         file_put_contents( $path, $str );
      }
   }
} //class
