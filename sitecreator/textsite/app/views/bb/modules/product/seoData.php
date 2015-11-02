<?php
class SeoData {
	function createHtml( $content ) {
		extract( $content['product-detail'] );
	?>
	<div class="product-head">
		<div class="container"><h2><?php echo $keyword ?></h2></div>
	</div>
	<div class="info-content container">
		<div class="vertical-tabs-container">
			<div class="vertical-tabs">
				<a href="javascript:void(0)" class="js-vertical-tab vertical-tab is-active" rel="tab1">Lastest Search</a>
				<a href="javascript:void(0)" class="js-vertical-tab vertical-tab" rel="tab2">Ads1</a>
				<a href="javascript:void(0)" class="js-vertical-tab vertical-tab" rel="tab3">Ads2</a>
				<a href="javascript:void(0)" class="js-vertical-tab vertical-tab" rel="tab4">video</a>
			</div>

			<div class="vertical-tab-content-container">
				<a href="" class="js-vertical-tab-accordion-heading vertical-tab-accordion-heading is-active" rel="tab1">Lastest Search</a>
				<div id="tab1" class="js-vertical-tab-content vertical-tab-content">
					<p><?php echo $content['lastestSearch']?></p>
				</div>

				<a href="" class="js-vertical-tab-accordion-heading vertical-tab-accordion-heading" rel="tab2">Ads1</a>
				<div id="tab2" class="js-vertical-tab-content vertical-tab-content">
					<?php $this->ads1( $content )?>
				</div>

				<a href="" class="js-vertical-tab-accordion-heading vertical-tab-accordion-heading" rel="tab3">Ads2</a>
				<div id="tab3" class="js-vertical-tab-content vertical-tab-content">
					<?php $this->ads2( $content )?>
				</div>

				<a href="" class="js-vertical-tab-accordion-heading vertical-tab-accordion-heading" rel="tab4">Video</a>
				<div id="tab4" class="js-vertical-tab-content vertical-tab-content">
					<p><?php $this->video( $keyword )?></p>
				</div>
			</div>
		</div>
	</div>
	
	<?php
	}

	function video( $keyword ) {
		$iframe = '<h4>' . ucfirst( Helper::getSearchKey( $keyword ) ) . ' Related VDO</h4>';
      	$iframe .= '<iframe width="100%" height="400" ';
     	$iframe .= 'src="http://youtube.com/embed?listType=search;list=' . Helper::getSearchKey( $keyword ) . '" frameborder="0">';
      	$iframe .= '</iframe>';
      	echo $iframe;
	}

	

	function ads1( $content ) {
		extract( $content['spin-content'] );
	?>
		<div class="ad">
			<h3><?php echo $title1?></h3>
			<p><?php echo $ad1?></p>
			<p><?php echo $ad_desc?></p>
			<p><?php echo $more_info1?></p>
		</div>
		
	<?php
	}

	function ads2( $content ) {
		extract( $content['spin-content'] );
	?>
		<div class="ad">
			<h3><?php echo $title2?></h3>
			<p><?php echo $ad2?></p>
			<p><?php echo $more_info2?></p>
		</div>
	<?php
	}
}