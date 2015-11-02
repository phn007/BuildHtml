<?php
class ProductList {
	function createHtml( $products ) {
	?>
		<section id="cat-list">
	<?php
		$productItems = $products['category-group'];
		$i = 0;
		foreach ( $productItems as $productFile => $items ):
			$key = key( $items );
			$catName = $items[$key]['category'];
	?>
			<div class="category-title">
				<h2 class="linetext">
					<span><a href="<?php echo $this->getCategoryLink( $catName )?>"><?php echo $catName?></a></span>
				</h2>
				<a href="<?php echo $this->getCategoryLink( $catName )?>">Shop All</a>
			</div>
			<div class="category-items"><?php $this->displayItems( $items )?></div>	
	<?php 
			$i++;
			if ( $i > 3 ) break;
		endforeach; 
	?>
		</section>
	<?php
	}

	function displayItems( $items ) {
		$count = 0;
		foreach ( $items as $item ):
			if ( ++$count > 3 ) break;
			$keywordForTag = $this->cleanDoubleQuote( $item['keyword'] );
	?>
		<div class="item">
			<div class="image">
				<a title="<?php echo $keywordForTag?>" href="<?php echo $item['permalink']?>">
					<img src="<?php echo BLANK_IMG?>" data-echo="<?php echo $item['image_url']?>" alt="<?php echo $keywordForTag?>">
				</a>
			</div>
			<div class="info">
				<h3><a title="<?php echo $keywordForTag?>" href="<?php echo $item['permalink']?>"><?php echo $this->getTitle( $item['keyword'] )?></a></h3>
				<div class="brand">
					<a href="<?php echo $this->getBrandLink( $item['brand'])?>"><?php echo $item['brand']?></a>
				</div>
				<div class="price">$<?php echo $item['price']?></div>
				
			</div>
		</div>
	<?php
		endforeach;
	}

	function getTitle( $keyword ) {
		return ucwords( strtolower( $keyword ) );
	}

	function getBrandLink( $brandName ) {
		$brandName = Helper::clean_string( $brandName );
		return HOME_URL . 'bnd/' . $brandName . FORMAT;
	}

	function getCategoryLink( $catName ) {
		$catName = Helper::clean_string( $catName );
		return HOME_URL . 'cat/' . $catName . FORMAT;
	}

	function cleanDoubleQuote( $str ) {
		return str_replace( '"', '', $str );
	}
}