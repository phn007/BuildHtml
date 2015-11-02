<?php
trait SiteInfomation
{	
	function getSiteDescription( $site_category )
	{
		$spinner = $this->component( 'textspinner' );
		$site_desc = $spinner->spinSiteDescription( $site_category );
		return trim( $site_desc );
	}
	
	function getSiteAuthor()
	{
		$path = FILES_PATH . 'author_name.txt';
		if ( ! file_exists( $path ) )
		{
			echo "author_name.txt file not found!!!";
			exit( 0 );
		}
		
		$file = file( $path );
		shuffle( $file );
		return trim( $file[0] );
	}

	function getProdRoute()
	{
		$prod_route = range( 'a', 'z' );
		shuffle( $prod_route );
		return trim( $prod_route[0] );
	}
}