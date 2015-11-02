<?php
class StaticPageModel extends AppComponent {
	use GetContent;
	use ReplaceContent;
	use StaticPageSeoTags;

	function about() {
		return $this->aboutContent();
	}

	function contact() {
		return $this->contactContent();
	}

	function privacy() {
		return $this->privacyContent();
	}

	function getSeoTags( $title ) {
		return $this->getStaticPageSeoTags( $title );
	}
}

/**
 * TRAIT
 * --------------------------------------------------------------------------------
*/
trait StaticPageSeoTags {
	function getStaticPageSeoTags( $title ) {
		$tags = $this->setSeoTags( $title );
		$tagCom = $this->component( 'seoTags' );
		return $tagCom->createSeoTags( $tags );
	}

	function setSeoTags( $title ) {
		$tags['title'] = $title;
		$tags['author'] = AUTHOR;
		$tags['robots'] = 'noindex, follow';
		return $tags;
	}
}

trait ReplaceContent {
	function aboutContent() {
		$content = $this->getContent( 'about-us' );
		$content = $this->replaceURL( $content );
		return $this->replaceSiteName( $content );
	}

	function contactContent() {
		$content = $this->getContent( 'contact-us' );
		$content = $this->replaceURL( $content );
		$content = $this->replaceSiteName( $content );
		return $this->replaceDomain( $content );
	}

	function privacyContent() {
		$content = $this->getContent( 'privacy-policy' );
		$content = $this->replaceURL( $content );
		$content = $this->replaceSiteName( $content );
		return $this->replaceDomain( $content );
	}

	function replaceURL( $content ) {
		return str_replace( '[%URL%]', HOME_URL, $content );
	}

	function replaceSiteName( $content ) {
		return str_replace( '[%SITENAME%]', SITE_NAME, $content );
	}

	function replaceDomain( $content ) {
		return str_replace( '[%DOMAIN%]', $this->getDomain(), $content );
	}

	function getDomain() {
		$domain = rtrim( HOME_URL, '/' );
		$arr = explode( '/', $domain );
		return end( $arr );
	}
}

trait GetContent {

	function getContent( $page ) {
		$path = $this->staticContentPath() . $page . '.html';
		return $this->readContentFromFile( $path );
	}

	function readContentFromFile( $path ) {
		return file_get_contents( $path );
	}

	function staticContentPath() {
		return BASE_PATH . 'files/staticpage/';
	}
}
