<section class="main-product">
	<?php if ( isset( $product['productDetail'] ) ) echo $product['productDetail'] ?>
</section>

<section class="related">
	<?php if ( isset( $product['relatedProducts'] ) ) echo $product['relatedProducts'] ?>
</section>

<section class="product-info">
	<?php if ( isset( $product['seo-data'] ) ) echo $product['seo-data'] ?>
</section>

<section>
	<div class="pagination">
		<?php if ( isset( $product['pagination'] ) ) echo $product['pagination'] ?>
	</div>
</section>

