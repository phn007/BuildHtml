<?php
trait RelatedProducts {
	use GetDataFromCategories;
	use GetProductFilename;
	use GetProductContents;
	use RandomRelatedProduct;
	use AddGotoLink;

	function setRelatedProducts() {
		$this->dbCom = $this->component( 'textdatabase' );
		$catSlug = $this->getCategorySlug();
		$productFiles = $this->getDataFromCategoriesFile( $catSlug );
		$productFilename = $this->getProductFilename( $productFiles );
		$productContents = $this->getProductContents( $productFilename );
		$products = $this->randomRelatedProduct( $productContents );
		return $this->addGotoLink( $productFilename, $products );
	}

	function getCategorySlug() {
		return Helper::clean_string( $this->productDetail['category'] );
	}
}

trait AddGotoLink {
	function addGotoLink( $productFile, $products ) {
		foreach ( $products as $productKey => $product ) {
			$data[$productKey] = $this->addLinkIntoRelatedProductItem( $product, $productFile, $productKey );
		}
		return $data;
	}

	function addLinkIntoRelatedProductItem( $product, $productFile, $productKey ) {
		$permalink = $this->getPermalink( $productFile, $productKey ); //link_trait.php - Permalink Trait
		$product['goto'] = $this->getGotoLink( $productFile, $productKey, $product['affiliate_url'], $permalink );
		return $product;
	}
}

trait RandomRelatedProduct {
	function randomRelatedProduct( $productContents ) {
		$keys = $this->getProductKeys( $productContents );
		$keys = $this->removeCurrentProduct( $keys );
		$productKeys = $this->randomProductKeys( $keys );
		return $this->getProductDetailFromKey( $productKeys, $productContents );
	}

	function getProductDetailFromKey( $productKeys, $productContents ) {
		foreach ( $productKeys as $key ) {
			$data[$key] = $productContents[$key];
		}
		return $data;
	}

	function randomProductKeys( $keys ) {
		shuffle( $keys );
		$productNumber = $this->getProductNumber( $keys );
		return array_splice( $keys, 0, $productNumber );
	}

	function getProductNumber( $keys ) {
		$num = count( $keys );
		return $num < RELATED_PRODUCT_NUMBER ? $num : RELATED_PRODUCT_NUMBER;
	}

	function getProductKeys( $productContents ) {
		return array_keys( $productContents );
	}

	function removeCurrentProduct( $keys ) {
		unset( $keys[$this->productKey] );
		return $keys;
	}
}

trait GetProductContents{
	function getProductContents( $productFilename ) {
		$path = $this->getProductFilePath( $productFilename );
		return $this->readProductContents( $path );
	}

	function readProductContents( $path ) {
		return $this->dbCom->getContentFromSerializeTextFile( $path );
	}

	function getProductFilePath( $productFilename ) {
		return $this->dbCom->setProductDirPath() . $productFilename . '.txt';
	}
}

trait GetProductFilename {
	function getProductFilename( $productFiles ) {
		return $this->randomFilename( $productFiles );
	}

	function randomFilename( $productFiles ) {
		shuffle( $productFiles['items'] );
		return $productFiles['items'][0];
	}
}

trait GetDataFromCategories {
	function getDataFromCategoriesFile( $catSlug ) {
		$path = $this->getCategoriesFilePath();
		$contents = $this->getCategoriesContent( $path );
		return $this->getProductFilenameFromContentsByCatSlug( $contents, $catSlug );
	}

	function getProductFilenameFromContentsByCatSlug( $contents, $catSlug ) {
		return $contents[$catSlug];
	}

	function getCategoriesContent( $path ) {
		return $this->dbCom->getContentFromSerializeTextFile( $path );
	}

	function getCategoriesFilePath() {
		return $this->dbCom->setCategoryDirPath();
	}
}

