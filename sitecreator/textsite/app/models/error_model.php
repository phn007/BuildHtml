<?php
class ErrorModel extends AppComponent {
	function getSeoTags() {
		$tags = $this->setSeoTags();
		$tagCom = $this->component( 'seoTags' );
		return $tagCom->createSeoTags( $tags );
	}
	function setSeoTags() {
		$tags['title'] = 'Page Not Found';
		$tags['author'] = AUTHOR;
		$tags['robots'] = 'noindex, follow';
		return $tags;
	}
}