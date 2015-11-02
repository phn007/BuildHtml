<?php
class TopProductList {
	function createHtml( $productItems ) {
	?>
		<section class="top-product-list container">
			<div class="bestsell">
				<h2>Lastest Products</h2>
				<div class="bestsell-content">
					<?php $this->bestSellerItems( $productItems['group-one'] )?>
				</div>
			</div>
			<div class="recommend">
				<h2>Recommended</h2>
				<div class="recommend-content">
					<?php $this->recommendItems( $productItems['group-two'] );?>
				</div>
			</div>
		</section>
	<?php
		
	}

	function bestSellerItems( $productItems ) {
		$key = $this->getProductGroupKey( $productItems );
		$items = $productItems[$key];
		$count = 0;
		foreach ( $items as $productKey => $item ) {
			if ( ++$count > 9 ) break;
			$keywordForTag = $this->cleanDoubleQuote( $item['keyword'] );
		?>
			<div class="item">
				<div class="image">
					<a title="<?php echo $keywordForTag?>" href="<?php echo $item['permalink']?>">
						<img src="<?php echo BLANK_IMG?>" data-echo="<?php echo $item['image_url']?>" alt="<?php echo $keywordForTag?>">
					</a>
				</div>
				<div class="info">
					<h3><a title="<?php echo $keywordForTag?>" href="<?php echo $item['permalink']?>"><?php echo $item['keyword']?></a></h3>
					<div class="price">$<?php echo $item['price']?></div>
					<a class="button" title="<?php echo $keywordForTag?>" href="<?php echo $item['permalink']?>">More Detail</a>
				</div>
			</div>
		<?php
		}
	}

	function recommendItems( $productItems ) {
		$key = $this->getProductGroupKey( $productItems );
		$items = $productItems[$key];
		foreach ( $items as $productKey => $item ) {
			$keywordForTag = $this->cleanDoubleQuote( $item['keyword'] );
		?>
			<div class="item">
				<div class="view effect">
					<img src="<?php echo BLANK_IMG?>" data-echo="<?php echo $item['image_url']?>" alt="<?php ?>">
					<div class="mask">
	    				<h3><a title="<?php echo $keywordForTag?>" href="<?php echo $item['permalink']?>"><?php echo $this->getTitle( $item['keyword'] )?></a></h3>
		    			<p>$100</p>
	    				<a title="<?php echo $keywordForTag?>" href="<?php echo $item['permalink']?>" class="info">Read More</a>
	    			</div>
				</div>
			</div>
		<?php
		}
	}

	function getTitle( $keyword ) {
		return ucwords( strtolower( $keyword ) );
	}

	function getProductGroupKey( $productItems ) {
		return key( $productItems );
	}

	function cleanDoubleQuote( $str ) {
		return str_replace( '"', '', $str );
	}
}

