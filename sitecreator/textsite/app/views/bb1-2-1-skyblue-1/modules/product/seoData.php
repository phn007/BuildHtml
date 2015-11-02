<?php
class SeoData {
	function createHtml( $content ) {
		extract( $content['product-detail'] );
	?>
		<h2><?php echo $keyword?></h2>
		<ul class="accordion-tabs-minimal">
		  <li class="tab-header-and-content">
		    <a href="#" class="tab-link is-active">Keywords</a>
		    <div class="tab-content">
		      <p><?php echo $content['lastestSearch']?></p>
		    </div>
		  </li>
		  <li class="tab-header-and-content">
		    <a href="#" class="tab-link">Spons1</a>
		    <div class="tab-content">
		      <p><?php $this->ads1( $content )?></p>
		    </div>
		  </li>
		  <li class="tab-header-and-content">
		    <a href="#" class="tab-link">Spons2</a>
		    <div class="tab-content">
		      <p><?php $this->ads2( $content )?></p>    
		    </div>
		  </li>
		  <li class="tab-header-and-content">
		    <a href="#" class="tab-link">Video</a>
		    <div class="tab-content">
		      <p><?php $this->video( $keyword )?></p>
		    </div>
		  </li>
		</ul>

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