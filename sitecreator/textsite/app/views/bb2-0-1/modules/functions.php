<?php
include_once 'html.php';

$data['current-page'] = $currentPage;
if ( $currentPage == 'product-page' ) {
	$data['product-detail'] = $productDetail;
	$data['spin-content'] = $spinContent;
}

$header = Html::get( 'main', 'header', $data );
$footer = Html::get( 'main', 'footer', $data );

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
	case 'html-sitemap-page':
		$htmlSitemap = htmlSitemapPage( $categoryList, $brandList );
		break;
}

/**
 * FUNCTIONS SECTION
 * ----------------------------------------------------------------------------------------------------------
*/

/*
	Html::get( FunctionNmae, ID-Key, Data ); //see trait Components on html.php
*/

function homePage( $productItems=null, $categoryList=null, $brandList=null ) {
	if ( isset( $productItems ) ) {
		$topProductList = Html::get( 'homepage', 'topProductList', $productItems );
		$productList = Html::get( 'homepage', 'productList', $productItems );
		$cycleProducts = Html::get( 'homepage', 'cycleList', $productItems );
	}	

	if ( isset( $categoryList ) && isset( $brandList ) ) {
		$categoryLinkList = Html::get( 'homepage', 'categoryLinkList', array( $categoryList, $brandList ) );
	}
	
	return array(
		'cycleProductList' => $cycleProducts,
		'topProductList' => $topProductList,
		'productList' => $productList,
		'categoryLinkList' => $categoryLinkList
	);
}

function htmlSitemapPage( $categoryList, $brandList ) {
	return Html::get( 'htmlSitemapPage', 'linkList', array( $categoryList, $brandList ) );	
}

function productPage( $productDetail, $spinContent, $relatedProducts, $paging, $lastestSearch ) {
	$content = array( 'product-detail' => $productDetail, 'spin-content' => $spinContent );
	$seoContent = array( 'product-detail' => $productDetail, 'spin-content' => $spinContent, 'lastestSearch' => $lastestSearch );
	$relatedProducts_2 = array_splice( $relatedProducts, 10 );

	return array(
		'breadcrumb' => Html::get( 'productpage', 'breadcrumb', $productDetail ),
		'productDetail' => Html::get( 'productpage', 'productDetail', $content ),
		'relatedProducts' => Html::get( 'productpage', 'relatedProducts', $relatedProducts ),
		'pagination' => Html::get( 'productpage', 'pagination', $paging ),
		'seo-data' => Html::get( 'productpage', 'seoData', $seoContent ),
		'sidebarProducts' => Html::get( 'productpage', 'sidebar', $relatedProducts_2 )
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

