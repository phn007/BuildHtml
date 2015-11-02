<?php
class CycleProductList {

	function createHtml( $productItems ) {
		// echo "<pre>";
		// print_r( $productItems['group-one'] );
		// echo "</pre>";
		$data = $this->getProductData( $productItems );
		?>
		<section id="home-top">
			<div class="cycle-slideshow" 
					data-cycle-loader=true
					data-cycle-fx="flipHorz" 
					data-cycle-timeout="3000" 
					data-cycle-pause-on-hover="true"
					data-cycle-slides="> div">
			<?php
			foreach ( $data['cycle_products'] as $item ) {
			?>
				<div class="slide-item">
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
			<?php
			}	
			?>
			</div>
			<div class="new-product">
			<?php 
				$img_url1 = Helper::image_size( $data['new_products'][0]['image_url'], '125x125' );
				$img_url2 = Helper::image_size( $data['new_products'][1]['image_url'], '125x125' );
				$keyword1 = $data['new_products'][0]['keyword'];
				$keyword2 = $data['new_products'][1]['keyword'];
				$permalink1 = $data['new_products'][0]['permalink'];
				$permalink2 = $data['new_products'][1]['permalink'];
			?>
				<div id="new1">
					<div id="new1-info">
						<h2><?php echo $keyword1?></h2>
						<a title="<?php echo $keyword1 ?>" href="<?php echo $permalink1?>" class="button2">LEARN MORE</a>
					</div>
					<div id="new1-img">
						<a title="<?php echo $keyword1 ?>" href="<?php echo $permalink1?>">
							<img src="<?php echo $img_url1 ?>" alt="<?php echo $keyword1 ?>">
						</a>
					</div>
				</div>

				<div id="new2">
					<div id="new2-img">
						<a title="<?php echo $keyword2 ?>" href="<?php echo $permalink2 ?>">
							<img src="<?php echo $img_url2 ?>" alt="<?php echo $keyword2 ?>">
						</a>
					</div>
					<div id="new2-info">
						<h2><?php echo $keyword2 ?></h2>
						<a title="<?php echo $keyword2 ?>" href="<?php echo $permalink2 ?>" class="button1">LEARN MORE</a>
					</div>
				</div>
			</div>
		</section>
		<?php
	}

	function getProductData( $productItems ) {
		$i = 0;
		foreach ( $productItems['group-one'] as $items ) {
			foreach ( $items as $item ) {
				if ( $i <= 2 ) 
					$cycle_products[] = $item;
				if ( $i > 2 && $i < 5 )
					$new_products[] = $item;
				$i++;
			}
		}
		return array(
			'cycle_products' => $cycle_products,
			'new_products' => $new_products
		);
	}
	
}

