<div id="search-list">
	<h1>Search</h1>
	<?php
		//echo "<pre>";
		//print_r( $products );
		//echo "</pre>";
		
		if ( !isset( $products ) )
			return false;
	?>
	<?php foreach ( $products as $key => $product ) :?>
		<div class="search-product-item">
			<div class="search-product-image">
				<a rel="nofollow" title="<?php echo $key?>" href="<?php echo $product['affiliate_url']?>">
					<img src="<?php echo $product['image_url']?>" width='125'>
				</a>
			</div>
			<div class="search-product-detail">
				<h2>
					<a rel="nofollow" title="<?php echo $key?>" href="<?php echo $product['affiliate_url']?>">
						<?php echo $key?>
					</a>
				</h2>
				<p><?php echo $product['description']?></p>

			</div>
			<div class="search-product-price">
				<div id="price">$<?php echo $product['price'] ?></div>
				<div><a rel="nofollow" title="<?php echo $key?>" href="<?php echo $product['affiliate_url']?>">VISIT STORE</a></div>
			</div>
		</div>
		
	<?php endforeach ?>

</div>