<?php
class ClsPagination {
	function createHtml( $menu ) {
		extract( $menu['url'] );
		extract( $menu['status'] );
	?>

	<div class="pagination">
		<ul>
			<li><?php $this->createLink( $firstStatus, $firstUrl, 'First' );?></li>
			<li><?php $this->createLink( $prevStatus, $prevUrl, 'Prev' );?></li>
			<li><?php $this->createLink( $nextStatus, $nextUrl, 'Next' );?></li>
			<li><?php $this->createLink( $lastStatus, $lastUrl, 'Last' );?></li>
		</ul>
	</div>

	<?php
	}

	function createLink( $status, $url, $label ) {
		$class = null;
		if ( ! $status ) {
			$url = 'javascript:void(0)';
			$class = 'class="disabled"';
		}
		echo '<a ' . $class . ' href="' . $url . '">' . $label . '</a>';
	}
}