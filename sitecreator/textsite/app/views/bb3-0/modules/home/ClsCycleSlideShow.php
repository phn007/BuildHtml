<?php
class ClsCycleSlideShow {
	function createHtml( $productItems ) {
       	$cycleProducts = $this->getProductData( $productItems );
	?>
		<div class="cycle-slideshow" 
					data-cycle-loader=true
					data-cycle-fx="flipHorz" 
					data-cycle-timeout="3000" 
					data-cycle-pause-on-hover="true"
					data-cycle-slides="> div">
		<?php foreach ( $cycleProducts as $item ): ?>
			<div id="slide">
				<div id="slide-img">
					<a title="<?php echo $item['keyword']?>" href="<?php echo $item['permalink']?>">
						<img src="<?php echo $item['image_url']?>" alt="<?php echo $item['keyword']?>">
					</a>
				</div>
				<div id="slide-info">
					<h2><?php echo $item['keyword'] ?></h2> 
					<p><?php echo Helper::limit_words( $item['description'], 20 )?></p>
					<a title="<?php echo $item['keyword']?>" href="<?php echo $item['permalink'] ?>" class="button1">VIEW MORE</a>
					<a title="<?php echo $item['keyword']?>" href="<?php echo $item['permalink'] ?>" class="button2">VISIT STORE</a>
				</div>
			</div>
		<?php endforeach ?>

		</div>

	<?php
	}

	function getProductData( $productItems ) {
		foreach ( $productItems['group-one'] as $items );
		return $items;
	}
}