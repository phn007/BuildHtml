<?php
class CategoryListItems {
	private $catType;

	function createHtml( $params ) {
		$this->catType = $params['catType'];
		$categoryItems = $params['categoryItems'];
		$catName = key( $categoryItems );
	?>
		<section class="category main-container">
			<h2><?php echo ucwords( $catName )?> - <?php echo $this->catType?></h2>
			<div class="category-content">
				<?php $this->displayItems( $categoryItems[$catName] )?>
			</div>
		</div>
	<?php		
	}
	
	function displayItems( $items ) {
		foreach ( $items as $item ) {
			$this->items( $item );
		}
	}

	function items( $item ) {
		extract( $item );
		$keyword = ucfirst( strtolower( $keyword ) );
		$permalink = $item['permalink'];
	?>
		<div class="item">
			<a title="<?php echo $keyword?>" href="<?php echo $permalink?>"><?php echo Helper::showImage( $image_url, '250x250', $keyword )?></a>
			<h3><a href="<?php echo $permalink?>" title="<?php echo $keyword?>"><?php echo $keyword ?></a></h3>
			<div class="cat">
				<?php echo $this->getCatLink( $item )?>
			</div>
			<div class="price">$<?php echo $price?></div>
		</div>
	<?php
	}

	function getCatLink( $item ) {
		if ( $this->catType == 'Category' ) {
			$link = HOME_URL . 'brand/' . Helper::clean_string( $item['brand'] ) . FORMAT;
			return '<a title="' . $item['brand'] . '" href="' . $link . '">' . $item['brand'] . '</a>';
		}

		if ( $this->catType == 'Brand' ) {
			$link = HOME_URL . 'category/' . Helper::clean_string( $item['category'] ) . FORMAT;
			return '<a title="' . $item['category'] . '" href="' . $link . '">' . $item['category'] . '</a>';
		}
	}
}