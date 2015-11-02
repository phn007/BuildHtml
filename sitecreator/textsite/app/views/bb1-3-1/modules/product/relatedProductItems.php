<?php
class RelatedProductItems {

	function createHtml( $relatedProducts ) {
	?>
		<h2><span id="relate-title">You May Also Like</span></h2>
		<div class="related-content">
			<?php $this->items( $relatedProducts )?>
		</div>
	<?php
	}

	function items( $relatedProducts ) {
		foreach ( $relatedProducts as $product ):
			extract( $product );
	?>
		<div class="item">
			<a title="<?php $this->titleForTag( $keyword )?>" rel="nofollow" href="<?php echo $goto?>"><?php $this->showImage( $image_url, $keyword )?></a>
			<h3><a title="<?php $this->titleForTag( $keyword )?>" rel="nofollow" href="<?php echo $goto?>"><?php $this->headTitle( $keyword )?></a></h3>
			<div class="brand">
				<a title="<?php echo $brand?>" rel="nofollow" href="<?php $this->getBrandLink( $brand) ?>"><?php echo $brand?></a>
			</div>
			<div class="price">$<?php echo $price?></div>
		</div>
	<?php
		endforeach;
	}

	function getBrandLink( $brand) {
		echo HOME_URL . 'brand/' . Helper::clean_string( $brand ) . FORMAT;
	}

	function headTitle( $keyword ) {
		$title = strtolower( $keyword );
		echo ucwords( $title );
	}

	function titleForTag( $keyword ) {
		echo str_replace( '"', '', $keyword );
	}

	function showImage( $image_url, $keyword ) {
		$alt = str_replace( '"', '', $keyword );
		echo '<img src="' . BLANK_IMG . '" data-echo="' . $image_url . '" alt="' . $alt . '">';
	}
}