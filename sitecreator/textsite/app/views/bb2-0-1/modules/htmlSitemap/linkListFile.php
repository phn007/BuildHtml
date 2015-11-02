<?php
class LinkListFile {
	function createHtml( $list ) {
	?>
	<section class="sitemap-link-list">
	<div class="link-content main-container">
		<div class="title">
			<h3><a href="<?php echo HOME_URL?>categories<?php echo FORMAT?>">Categories</a></h3>
		</div>
		<div class="link-list">
			<?php $this->displayLinkItems( $list[0] )?>
		</div>
		
		<div class="title">
			<h3><a href="<?php echo HOME_URL?>brands<?php echo FORMAT?>">Brands</a></h3>
		</div>
		<div class="link-list">
			<?php $this->displayLinkItems( $list[1] )?>
		</div>
	</div>
	</section>
	<?php
	}

	function displayLinkItems( $list ) {
		foreach ( $list as $catName => $url ):
	?>
		<div class="link"><a href="<?php echo $url . FORMAT?>"><?php echo $catName?></a></div>
	<?php
		endforeach;
	}
}