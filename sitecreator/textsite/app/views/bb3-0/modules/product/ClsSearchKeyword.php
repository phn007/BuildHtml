<?php
class ClsSearchKeyword {
	function createHtml( $data ) {
		extract( $data['productDetail'] );
	?>
	<ul class="accordion-tabs">
		<li class="tab-header-and-content">
			<a href="javascript:void(0)" class="is-active tab-link">tab1</a>
			<div class="tab-content">
			<h3>Search Keywords</h3>
				<p><?php echo $data['lastestSearch']?></p>
			</div>
		</li>
		<li class="tab-header-and-content">
			<a href="javascript:void(0)" class="tab-link">tab2</a>
			<div class="tab-content">
			<p><?php $this->_video( $keyword )?></p>
			</div>
		</li>
	</ul>
	<?php
	}

	function _video( $keyword ) {
		$iframe = '<h4>' . ucfirst( Helper::getSearchKey( $keyword ) ) . ' Related VDO</h4>';
      	$iframe .= '<iframe width="100%" height="400" ';
     	$iframe .= 'src="http://youtube.com/embed?listType=search;list=' . Helper::getSearchKey( $keyword ) . '" frameborder="0">';
      	$iframe .= '</iframe>';
      	echo $iframe;
	}
}