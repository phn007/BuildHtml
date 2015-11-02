<?php
class ClsBreadcrumb {
	function createHtml( $data = null ) {
	?>
		<div id="breadcrumb-container">
			<h1><?php $this->productName( $data )?></h1>
			<div id="breadcrumb"><?php $this->breadcrumb( $data )?></div>
		</div>
	<?php
	}

	function productName( $data ) {
		echo $data['keyword'];
	}

	function breadcrumb( $data ) {
		extract( $data );
		$this->_home();
		echo ' > ';
		$this->_category( $category, $categoryLink );
		echo ' > ';
		$this->_product( $keyword, $permalink );
	}

	function _home() {
		echo '<a title="' . HOME_URL . '" href="' . HOME_URL . 'index.html">Home</a>';
	}

	function _category( $category, $categoryLink ) {
		echo '<a title="' . $category . '" href="' . $categoryLink . FORMAT . '">' . $category . '</a>';
	}

	function _product( $keyword, $permalink ) {
		$title = str_replace( '"', '', $keyword );
		echo '<a title="' . $title . '" href="' . $permalink . '">' . $keyword . '</a>';
	}
}