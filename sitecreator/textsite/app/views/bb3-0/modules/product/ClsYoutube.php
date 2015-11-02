<?php
class ClsYoutube { 
	function createHtml( $data ) {

		// echo "<pre>";
		// print_r( $data );
		// echo "</pre>";

		$keyword = $data['keyword'];

		$iframe = '<h4>' . ucfirst( Helper::getSearchKey( $keyword ) ) . ' Related VDO</h4>';
      	$iframe .= '<iframe width="100%" height="400" ';
     	$iframe .= 'src="http://youtube.com/embed?listType=search;list=' . Helper::getSearchKey( $keyword ) . '" frameborder="0">';
      	$iframe .= '</iframe>';
      	echo $iframe;
	}
}