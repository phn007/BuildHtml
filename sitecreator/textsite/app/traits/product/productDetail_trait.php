<?php
trait ProductDetail {

	function setProductDetail() {
		$this->dbCom = $this->component( 'textdatabase' );
		$productPath = $this->productPath();
		$productItemList = $this->productItemList( $productPath );
		$productItem = $this->productItem( $productItemList );
		$productItem = $this->addPermalinkIntoProductItem( $productItem );
		$productItem = $this->addGotoLinkIntoProductItem( $productItem );
		$productItem = $this->addBrandLinkIntoProductItem( $productItem );
		$productItem = $this->addCategoryLinkIntoProductItem( $productItem );
		return $productItem;
	}

	function addPermalinkIntoProductItem( $productItem ) {
		$productItem['permalink'] = $this->getPermalink( $this->productFile, $this->productKey );
		return $productItem;
	}

	function addCategoryLinkIntoProductItem( $productItem ) {
		$filename = Helper::clean_string( $productItem['category'] );
		$productItem['categoryLink'] = $this->getCategoryLink( 'category', $filename ); //permalink trait
		return $productItem;
	}

	function addBrandLinkIntoProductItem( $productItem ) {
		$filename = Helper::clean_string( $productItem['brand'] );
		$productItem['brandLink'] = $this->getCategoryLink( 'brand', $filename ); //permalink trait
		return $productItem;
	}

	function addGotoLinkIntoProductItem( $productItem ) {
		$link = $this->getGotoLink( $this->productFile, $this->productKey, $productItem['affiliate_url'], $productItem['permalink'] ); //permalink trait
		$productItem['goto'] = $link;
		return $productItem;
 	}

	function productItem( $productItemList ) {
		try {
			if ( !array_key_exists( $this->productKey, $productItemList ) ) 
				throw new CustomException( 'Product Key Not Found.' );
			return $productItemList[$this->productKey];
		} catch( CustomException $e ) {
			$e->handle();
		}
	}

	function productItemList( $productPath ) {
		return $this->dbCom->getContentFromSerializeTextFile( $productPath );
	}

	function productPath() {
		$productDir = $this->dbCom->setProductDirPath();
		return $productDir . $this->productFile . '.txt';
	}
}