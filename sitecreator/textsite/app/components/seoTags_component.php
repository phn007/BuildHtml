<?php
class SeoTagsComponent {
	
	function createSeoTags( $contents ) {
		$tags = null;
		foreach ( $contents as $tagName => $content ) {
			$tags .= $this->callFunction( $tagName, $content ) . PHP_EOL;
		}
		return $tags;
	}
	
	function callFunction( $method, $arg ) {
		if ( is_callable( array( $this, $method ) ) )
			return $this->$method( $arg );
	}
	
	function title( $content ) {
		return '<title>' . $content . '</title>';
	}
	
	function keywords( $content ) {
		return '<meta name="keywords" content="' . $content . '" />';
	}
	
	function description( $content ) {
		return '<meta name="description" content="' . $content . '" />';
	}
	
	function author( $content ) {
		return '<meta name="author" content="' . $content . '" />';
	}
	
	function robots( $content ) {
		return '<meta name="robots" content="' . $content . '" />';
	}
	
	function linkCanonical( $url ) {
		return '<link rel="canonical" href="' . $url . '" />';
	}

	function linkNext( $url ) {
		return '<link rel="next" href="' . $url . '" />';
	}

	function linkPrev( $url ) {
		return '<link rel="prev" href="' . $url . '" />';
	}

	//facebook
	
	function propertyLocale( $content ) {
		return '<meta property="og:locale" content="' . $content . '" />';
	}
	
	function propertyType( $content ) {
		return '<meta property="og:type" content="' . $content . '" />';
	}
	
	function propertyTitle( $content ) {
		return '<meta property="og:title" content="' . $content . '" />';
	}
	
	function propertyDescription( $content ) {
		return '<meta property="og:description" content="' . $content. '" />';
	}
	
	function propertyUrl( $content ) {
		return '<meta property="og:url" content="' . $content . '" />';
	}
	
	function propertySiteName( $content ) {
		return '<meta property="og:site_name" content="' . $content . '" />';	
	}
	
	function propertyArticleTag( $content ) {
		return '<meta property="article:tag" content="' . $content . '" />';
	}
	
	function propertyArticleSection( $content ) {
		return '<meta property="article:section" content="' . $content . '" />';
	}
}