<?php
class ClsRelatedProducts {
	function createHtml( $products ) {
	?>
		<hr id="hr-related-topic">
		<div id="related-topic"><h2>Related Products</h2></div>
		<div id="related-content"><?php $this->displayRelatedItems( $products )?></div>

	<?php
	}

	function displayRelatedItems( $products ) {
		foreach ( $products as $product ) {
			extract( $product );
		?>
			<div class="related-item">
				<div class="item-image">
					<a title="<?php $this->_titleForTag( $keyword )?>" rel="nofollow" href="<?php echo $goto?>">
						<?php $this->_showImage( $image_url, $keyword )?>
					</a>
				</div>
				<div class="item-price">$<?php echo $price?></div>
				<hr class="hr-related-item">
				<h3>
					<a title="<?php $this->_titleForTag( $keyword )?>" rel="nofollow" href="<?php echo $goto?>">
						<?php $this->_headTitle( $keyword )?>
					</a>
				</h3>
			</div>
		<?php
		}
	}

	function _showImage( $image_url, $keyword ) {
		$alt = str_replace( '"', '', $keyword );
		echo '<img src="' . BLANK_IMG . '" data-echo="' . $image_url . '" alt="' . $alt . '">';
	}

	function _titleForTag( $keyword ) {
		echo str_replace( '"', '', $keyword );
	}

	function _headTitle( $keyword ) {
		$title = strtolower( $keyword );
		echo ucwords( $title );
	}
}