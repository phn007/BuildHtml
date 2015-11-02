<?php

class database
{
   function connectDatabase()
   {
      //$hostname = 'localhost';
      $hostname = '127.0.0.1';
      $username = 'root';
      $password = 'p2h5a1n1';
      $conn = mysqli_connect( $hostname, $username, $password ) or die ( 'could not connect to database' );
      return $conn;
   }


   function totalProductCount( $conn, $db_name )
   {
      if ( $this->selectDatabase( $conn, $db_name) )
      {
         if ( $this->checkTableExist( $conn, 'products' ) )
         {
            $sql = "SELECT COUNT(*) FROM products";
            $rs = mysqli_query( $conn, $sql );
            $row = mysqli_fetch_array( $rs );
            $num_products = $row[0];
         }
         else
         {
            $num_products = 0;
         }
      }
      else
      {
         $num_products = 0;
      }
      return $num_products;
   }


   function selectDatabase( $conn, $db )
   {
      return mysqli_select_db( $conn, $db );
   }


   function deleteDatabase( $conn, $db_name )
   {
      $sql = "DROP DATABASE IF EXISTS " . $db_name;
      if ( mysqli_query( $conn, $sql ) )
      {
         echo "Delete " . $db_name . ' database';
         echo "\n";
      }
      else
      {
         die( "Error creating database: " . mysqli_error($conn) );
         echo "\n";
         //log
      }
   }


   function createDatabase( $conn, $db_name )
   {
      $sql = "CREATE DATABASE " . $db_name;
      if ( mysqli_query( $conn, $sql ) )
      {
         echo "Create " . $db_name . " database";
         echo "\n";
         return true;
      }
      else
      {
         //die( "Error creating database: " . mysqli_error($conn) );
         //log
         return false;
      }
   }


   function checkTableExist( $conn, $table_name )
   {
      // Select 1 from table_name will return false if the table does not exist.
      return mysqli_query( $conn, 'select 1 from ' . $table_name );
   }
}
