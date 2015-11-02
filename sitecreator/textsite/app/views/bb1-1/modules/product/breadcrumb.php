<?php
class Breadcrumb {
	function createHtml( $productDetail ) {
		extract( $productDetail );
		$this->home();
		$this->category( $category, $categoryLink );
		$this->product( $keyword, $permalink );
	}

	function category(  $category, $categoryLink ) {
	?>
		<a title="<?php echo $category?>" href="<?php echo $categoryLink?>"><?php echo $category?></a>
	<?php
	}

	function product( $keyword, $permalink ) {
		$title = str_replace( '"', '', $keyword );
	?>
		<a title="<?php echo $title?>" href="<?php echo $permalink?>"><?php echo $keyword?></a>
	<?php
	}

	function home() {
		echo '<a title="' . HOME_URL . '" href="' . HOME_URL . '">Home</a>';
	}
}