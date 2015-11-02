<?php
include APP_PATH . 'traits/product/productDetail_trait.php';
include APP_PATH . 'traits/product/spinContent_trait.php';
include APP_PATH . 'traits/product/lastestSearch_trait.php';
include APP_PATH . 'traits/product/relatedProducts_trait.php';
include APP_PATH . 'traits/product/paginatorProducts_trait.php';
include APP_PATH . 'traits/product/productSeoTags_trait.php';
include APP_PATH . 'traits/link_trait.php';
include APP_PATH . 'traits/networkLink_trait.php';

/**
* Product Model
*/
class ProductModel extends AppComponent {
	use ProductDetail;
	use SpinContent;
	use LastestSearch;
	use RelatedProducts;
	use PaginatorProducts;
	use Permalink, CategoryLink, GotoLink, ProsperentAPI, Viglink;
	use ProductSeoTags;

	private $productFile;
	private $productKey;
	private $dbCom;
	private $productDetail;

	private $spinCom;
	private $wordlib_path;
	private $text_path;
	private $spinContent;

	private $relatedProducts;
	private $seoTags;

	private $pagingUrl;
	private $pagingState;

	function __set( $name, $value ) {
      	$this->{$name} = $value;
 	}

   	function __get( $name ) {
      	return $this->{$name};
   	}

   	function defineParameter( $params ) {
		if ( isset( $params[0] ) ) $this->productFile = $params[0];
		if ( isset( $params[1] ) ) $this->productKey = str_replace( FORMAT, '', $params[1] );
   	}

	function getProductDetail() {
		$this->productDetail = $this->setProductDetail(); //ProductDetail Trait
	}

	function getSpinContent() {
		$this->spinContent = $this->setSpinContent(); //SpinContent Trait
	}

	function getLastestSerach() {
		$this->lastestSearch = $this->setLastestSearch(); //LastestSearch Trait;
	}

	function getRelatedProducts() {
		$this->relatedProducts = $this->setRelatedProducts(); //RelatedProducts Trait
	}

	function getPagination() {
		$paging = $this->setPagination(); //PaginatorProducts Trait
		$this->pagingUrl = $paging['url'];
		$this->pagingState = $paging['state'];
	}

	function getSeoTags() {
		$this->getkeywordTags();
		return $this->getProductSeoTags(); //ProductSeoTags Trait
	}

	function getKeywordTags() {
		$this->tags = $this->createKeywordTags( $this->productKey );
	}

	function createKeywordTags( $keyword ) {
		$tags = explode( '-', $keyword);
		$tags = array_filter( $tags );
		$i = 1;
		foreach ( $tags as $tag ) {
			$num = strlen ( $tag );
			if ( $num > 1 ) {
				$data[ 'tag' . $i ] = $tag;
				$i++;
			}
		}
		return $data;
	}
}

