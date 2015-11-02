<?php
class SidebarProducts {
	function createHtml( $products ) {
		foreach ( $products as $key => $prod ):
	?>
		<div class="sidebar-content">
		<a title="<?php echo $key?>" rel="nofollow" href="<?php echo $prod['goto']?>">
			<img src="<?php echo $prod['image_url']?>" width="75">
		</a>
		<h3><?php echo $key?></h3>
		<p id="sidebar-price">$<?php echo $prod['price']?></p>

		</div>
		<div class="sidebar-clear"></div>
	<?php	
		endforeach;
	}
}