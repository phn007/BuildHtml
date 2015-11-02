<?php

use webtools\app\extensions\textSpinner;

include WT_APP_PATH . 'extensions/textspinner.class.php';

class textspinnerComponent extends TextSpinner
{

   function spinSiteDescription( $site_category )
   {
      $textpath = TEXTSPIN_PATH . $site_category . '.txt';
      $wordlib_path = TEXTSPIN_PATH . $site_category . '.csv';
      $text = $this->spinText( $wordlib_path, $textpath );
      return $text;
   }


   //Overide Method ( excute )
   function spinText( $wordlib_path, $textpath )
   {
      $data = $this->readWordLibrary( $wordlib_path );
      $data = $this->createSpinFormatType( $data );
      $text = $this->randomTextContent( $textpath );
      $text = $this->replaceTextSpin( $data, $text );

      return $text;
   }


   //Overide Method
   function createSpinFormatType( $array )
   {
      foreach ( $array as $key => $str )
      {
         $key = strtolower( $key );
         shuffle( $str );

         if ( $key == 'shop' )
         {
            $string = trim( $str[0] );
         }
         elseif ( $key == 'more' )
         {
            $string = trim( $str[0] );
         }
         else
         {
            $string = trim( $str[0] ) . ', ' . trim( $str[1] ) . ', ';
         }

         $key = '[' . $key . ']';
         $data[ $key ] = $string;
      }
      return $data;
   }
}
