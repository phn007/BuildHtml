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
	return array(
		'cycleSlideShow' => Html::get( 'homePage', 'cycleSlideShow', $productItems ),
		'productList' => Html::get( 'homePage', 'productList', $productItems )
	);
}

function productPage( $productDetail, $spinContent, $relatedProducts, $paging, $lastestSearch ) {
	$productDetailData = array( 'detail' => $productDetail, 'spin' => $spinContent );
	$searchKeyword = array( 'lastestSearch' => $lastestSearch, 'productDetail' => $productDetail );
	return array(
		'breadcrumb' => Html::get( 'productPage', 'breadcrumb', $productDetail ),
		'productDetail' => Html::get( 'productPage', 'productDetail', $productDetailData ),
		'relatedProducts' => Html::get( 'productPage', 'relatedProducts', $relatedProducts),
		'pagination' => Html::get( 'productPage', 'pagination', $paging ),
		'searchKeyword' => Html::get( 'productPage', 'searchKeyword', $searchKeyword ),
	);
}

function categoryPage( $category, $paging, $catType ) {
	$params = array( 'categoryItems' => $category, 'catType' => $catType );
	return array(
		'items' => Html::get( 'categoryPage', 'categoryItems', $params ),
		'pagination' => Html::get( 'categoryPage', 'pagination', $paging )
	); 
}

function categoriesPage( $categories, $paging, $catType ) {
	$params = array( 'categoryItems' => $categories, 'catType' => $catType );
	return array(
		'links' => Html::get( 'categoriesPage', 'categories', $params ),
		'pagination' => Html::get( 'categoriesPage', 'pagination', $paging ),
	);
}

