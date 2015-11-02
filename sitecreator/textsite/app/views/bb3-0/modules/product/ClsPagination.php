<?php
class ClsPagination {
	function createHtml( $paging ) {
		extract( $paging['url'] );
		extract( $paging['state'] );
	?>
		<ul>
			<li><?php $this->createLink( $first, $firstUrl, 'First' );?></li>
			<li><?php $this->createLink( $prev, $prevUrl, 'Prev' );?></li>
			<li><?php $this->createLink( $next, $nextUrl, 'Next' );?></li>
			<li><?php $this->createLink( $last, $lastUrl, 'Last' );?></li>
		</ul>

	<?php
	}

	function createLink( $state, $url, $label ) {
		$class = null;
		if ( ! $state ) {
			$url = 'javascript:void(0)';
			$class = 'class="disabled"';
		}
		echo '<a ' . $class . ' href="' . $url . '">' . $label . '</a>';
	}
}