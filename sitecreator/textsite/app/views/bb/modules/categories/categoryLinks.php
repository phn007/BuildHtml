<?php
class CategoryLinks {
	function createHtml( $categories ) {
		$catItems = $categories['categoryItems'];
		$catType = $categories['catType'];
	?>
		<section class="categories container">
			<h2><?php echo $catType?></h2>
			<div class="link-content">
				<?php $this->items( $catItems )?>
			</div>
		</section>
	<?php	
	}

	function items( $catItems ) {
		foreach ( $catItems as $catName => $url ) {
			echo '<div class="item">';
			echo '<a href="' . $url . '">' . $catName . '</a>';
			echo '</div>';
		}
	}
}