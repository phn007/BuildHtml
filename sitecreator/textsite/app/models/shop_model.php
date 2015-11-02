<?php
include APP_PATH . 'traits/link_trait.php';
include APP_PATH . 'traits/networkLink_trait.php';

class ShopModel extends AppComponent {
	use AffiliateUrl;
	use Permalink;
	use ProsperentAPI;
	use Viglink;

	function getUrl( $params ) {
		$productFile = $this->getProductFile( $params );
		$productKey = $this->getProductKey( $params );
		$permalink = $this->getPermalink( $productFile, $productKey ); // link Trait
		$affiliateUrl = $this->getAffiliateUrl( $productFile, $productKey ); //AffiliateUrl Trait
		return $this->networkUrl( $affiliateUrl, $permalink );
	}

	function networkUrl( $affiliateUrl, $permalink ) {
		if ( NETWORK == 'prosperent-api' ) return $this->prosperentApi( $affiliateUrl, $permalink ); //networkLink_trait.php - ProsperentAPI Trait
		if ( NETWORK == 'viglink' ) return $this->viglink( $affiliateUrl );
	}

	function getProductFile( $params ) {
		return $params[0];
	}

	function getProductKey( $params ) {
		return $params[1];
	}
}

trait AffiliateUrl {
	function getAffiliateUrl( $productFile, $productKey ) {
		$dbCom = $this->component( 'textdatabase' );
		$path = $this->getProductFilePath( $dbCom, $productFile );
		$productItems = $this->readProductItemsFromFile( $dbCom, $path );
		return $productItems[$productKey]['affiliate_url'];
	}

	function getProductFilePath( $dbCom, $productFile ) {
		return $dbCom->setProductDirPath() . $productFile . '.txt';

	}

	function readProductItemsFromFile( $dbCom, $path ) {
		return $dbCom->getContentFromSerializeTextFile( $path );
	}
}


