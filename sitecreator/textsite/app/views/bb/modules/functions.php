<?php
include_once 'html.php';

$footerData['current-page'] = $currentPage;
if ( $currentPage == 'product-page' ) {
	$footerData['product-detail'] = $productDetail;
	$footerData['spin-content'] = $spinContent;
}

$header = Html::get( 'main', 'header' );
$footer = Html::get( 'main', 'footer', $footerData );

switch ( $currentPage ) {
	case 'home-page':
		$home = homePage( $productItems, $categoryList, $brandList );
		break;
	case 'product-page':
		$product = productPage( $productDetail, $spinContent, $relatedProducts, $paging, $lastestSearch );
		break;
	case 'category-page':
		$category = categoryPage( $category, $paging, $catType );
		break;
	case 'brand-page':
		$category = categoryPage( $category, $paging, $catType );
		break;
	case 'categories-page':
		$categories = categoriesPage( $categories, $paging, $catType );
		break;
	case 'brands-page':
		$categories = categoriesPage( $categories, $paging, $catType );
		break;
}

/**
 * FUNCTIONS SECTION
 * ----------------------------------------------------------------------------------------------------------
*/
function homePage( $productItems=null, $categoryList=null, $brandList=null ) {
	if ( isset( $productItems ) ) {
		$topProductList = Html::get( 'homepage', 'topProductList', $productItems );
		$productList = Html::get( 'homepage', 'productList', $productItems );
	}	
	if ( isset( $categoryList ) && isset( $brandList ) ) {
		$categoryLinkList = Html::get( 'homepage', 'categoryLinkList', array( $categoryList, $brandList ) );
	}
	return array(
		'topProductList' => $topProductList,
		'productList' => $productList,
		'categoryLinkList' => $categoryLinkList
	);
}

function productPage( $productDetail, $spinContent, $relatedProducts, $paging, $lastestSearch ) {
	$content = array( 'product-detail' => $productDetail, 'spin-content' => $spinContent );
	$seoContent = array( 'product-detail' => $productDetail, 'spin-content' => $spinContent, 'lastestSearch' => $lastestSearch );
	return array(
		'breadcrumb' => Html::get( 'productpage', 'breadcrumb', $productDetail ),
		'productDetail' => Html::get( 'productpage', 'productDetail', $content ),
		'relatedProducts' => Html::get( 'productpage', 'relatedProducts', $relatedProducts ),
		'pagination' => Html::get( 'productpage', 'pagination', $paging ),
		'seo-data' => Html::get( 'productpage', 'seoData', $seoContent )
	);
}

function categoryPage( $category, $paging, $catType ) {
	$params = array( 'categoryItems' => $category, 'catType' => $catType );
	return array(
		'items' => Html::get( 'categorypage', 'categoryItems', $params ),
		'pagination' => Html::get( 'categorypage', 'pagination', $paging )
	); 
}

function categoriesPage( $categories, $paging, $catType ) {
	$params = array( 'categoryItems' => $categories, 'catType' => $catType );
	return array(
		'links' => Html::get( 'categoriespage', 'categories', $params ),
		'pagination' => Html::get( 'categoriespage', 'pagination', $paging ),
	);
}