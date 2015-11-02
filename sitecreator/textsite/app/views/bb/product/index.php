<section class="container">
	<div class="breadcrumb">
		<?php if ( isset( $product['breadcrumb'] ) ) echo $product['breadcrumb']?>
	</div>
</section>

<section class="product-detail container">
	<?php if ( isset( $product['productDetail'] ) ) echo $product['productDetail'] ?>
</section>

<section class="related-products container">
	<?php if ( isset( $product['relatedProducts'] ) ) echo $product['relatedProducts'] ?>
</section>

<section>
	<div class="pagination">
		<?php if ( isset( $product['pagination'] ) ) echo $product['pagination'] ?>
	</div>
</section>

<section class="product-info">
	<?php if ( isset( $product['seo-data'] ) ) echo $product['seo-data'] ?>
</section>

