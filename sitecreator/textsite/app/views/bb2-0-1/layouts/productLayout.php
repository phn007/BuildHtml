<div class="main-container">
	<section>
		<div class="breadcrumb">
			<?php if ( isset( $product['breadcrumb'] ) ) echo $product['breadcrumb']?>
		</div>
	</section>
	<div id="product-content">[%CONTENT%]</div>
	<div id="product-sidebar">
		<?php if ( isset( $product['sidebarProducts'] ) ) echo $product['sidebarProducts']?>
	</div>
</div>