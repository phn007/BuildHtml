<?php
class ClsLinks {
	function createHtml( $data ) {
		$catItems = $data['categoryItems'];
		$catType = $data['catType'];
	?>
		<h2><?php echo strtoupper( $catType )?></h2>
		<div class="cat-link-content">
			<?php $this->items( $catItems )?>
		</div>

	<?php
	}

	function items( $catItems ) {
		foreach ( $catItems as $catName => $url ) {
			echo '<div class="cat-link-item">';
			echo '<a title="'. $catName .'" href="' . $url . '">' . $catName . '</a>';
			echo '</div>';
		}
	}
}