<?php

class ClsCategoryItems {
	use CategoryLink;
	
	function createHtml( $data ) {
		$this->catType = $data['catType'];
		$categoryItems = $data['categoryItems'];
		$catName = key( $categoryItems );
	?>
		<h2><?php echo strtoupper( $catName )?> - <?php echo strtoupper( $this->catType )?></h2>
		<div id="category-items"><?php $this->displayItems( $categoryItems[$catName] )?></div>
	<?php
	}


	function displayItems( $categoryItems ) {
		foreach ( $categoryItems as $key => $item ):
			$keywordForTag = $this->_cleanDoubleQuote( $key );
	?>
		<div class="category-list-item">
			<div class="category-list-image">
				<a title="<?php echo $keywordForTag?>" href="<?php echo $item['permalink']?>">
					<img src="<?php echo BLANK_IMG?>" data-echo="<?php echo $item['image_url']?>" alt="<?php echo $keywordForTag?>">
				</a>
			</div>
			<div class="category-list-price">$<?php echo $item['price']?></div>
			<hr class="hr-category-list">
			<h3>
				<a title="<?php echo $keywordForTag?>" href="<?php echo $item['permalink']?>">
					<?php echo $this->_getTitle( $item['keyword'] )?>
				</a>
			</h3>
		</div>

	<?php
		endforeach;
	}

	function _cleanDoubleQuote( $str ) {
		return str_replace( '"', '', $str );
	}

	function _getTitle( $keyword ) {
		return ucwords( strtolower( $keyword ) );
	}
}