<?php
class ClsProductList {
	use CategoryLink;

	function createHtml( $data ) {
		foreach ( $data['category-group'] as $productFile => $items ):
			$key = key( $items );
			$catName = $items[$key]['category'];
		?>
			<div class="home-category-title">
				<h2><a href="<?php echo $this->_getCategoryLink( $catName )?>"><?php echo strtoupper( $catName )?></a></h2>
			</div>
			<div class="home-category-items"><?php $this->displayItems( $items )?></div>	

	<?php
		endforeach;
	}

	function displayItems( $items ) {
		$count = 0;
		foreach ( $items as $item ):
			if ( ++$count > 5 ) break;
			$keywordForTag = $this->_cleanDoubleQuote( $item['keyword'] );
	?>
		<div class="product-list-item">
			<div class="product-list-image">
				<a title="<?php echo $keywordForTag?>" href="<?php echo $item['permalink']?>">
					<img src="<?php echo BLANK_IMG?>" data-echo="<?php echo $item['image_url']?>" alt="<?php echo $keywordForTag?>">
				</a>
			</div>
			<div class="product-list-price">$<?php echo $item['price']?></div>
			<hr class="hr-product-list">
			<h3><a title="<?php echo $keywordForTag?>" href="<?php echo $item['permalink']?>"><?php echo $this->_getTitle( $item['keyword'] )?></a></h3>
		</div>
	<?php
		endforeach;
	}


	function _getCategoryLink( $catName ) {
		$catName = Helper::clean_string( $catName );
		return $this->getCategoryLink( 'category', $catName ); //see CategoryLink trait
	}

	function _cleanDoubleQuote( $str ) {
		return str_replace( '"', '', $str );
	}

	function _getTitle( $keyword ) {
		return ucwords( strtolower( $keyword ) );
	}
}