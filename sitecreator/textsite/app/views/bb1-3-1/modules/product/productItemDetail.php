<?php 
class ProductItemDetail {
	use Content;
	use SocialShare;


	function createHtml( $content ) {
		//$productDetail = $content['product-detail'];
		$spinContent = $content['spin-content'];
		extract($content['product-detail'] );
	?>
	<div class="prod-detail-content">
		<div class="image"><?php $this->image( $image_url, $keyword, $goto )?></div>
		<div class="info">
			<h1><?php $this->title( $goto, $keyword )?></h1>
			<?php $this->social( $permalink, $keyword );?>
			<div class="brand"><?php $this->brand( $brandLink, $brand )?></div>
			<hr>
			<div class="price">$<?php echo $price?></div>
			<div class="button">
				<span id="butt">
					<?php $this->button( $goto, $keyword )?>
				</span> 
			</div>
			<hr>
			<p class="desc"><?php echo $description?></p>
		</div>	
	</div>
	<?php
	}

	function social( $permalink, $keyword ) {
	?>
		<ul class="product-social">
			<li class="facebook">
				<a rel="nofollow" href="<?php echo $this->facebook( $permalink )?>"><i class='icon fa fa-facebook'></i>Share</a>
			</li>
			<li class="twitter">
				<a rel="nofollow" href="<?php echo $this->twitter( $permalink )?>"><i class='icon fa fa-twitter'></i>Tweet</a>
			</li>
			<li class="google-plus">
				<a rel="nofollow" href="<?php echo $this->googlePlus( $permalink, $keyword )?>"><i class='icon fa fa-google-plus'></i>Google+</a>
			</li>
			<li class="pinterest">
				<a rel="nofollow" href="<?php echo $this->pinterest( $permalink, $keyword )?>"><i class='icon fa fa-pinterest'></i>Pinterest</a>
			</li>
		</ul>
	<?php
	}
}

trait Content {
	function image( $image_url, $keyword, $goto ) {
		$title = $this->cleanDoubleQuote( $keyword );
	?>
		<a rel="nofollow" title="<?php echo $title?>" href="<?php echo $goto?>">
			<img src="<?php echo $image_url ?>" alt="<?php echo $title?>">
		</a>
	<?php
	}

	function title( $goto, $keyword ) {
		$title = $this->cleanDoubleQuote( $keyword );
	?>
		<a rel="nofollow" title="<?php echo $title?>" href="<?php echo $goto?>"><?php echo $keyword?></a>
	<?php
	}

	function brand( $brandLink, $brand ) { ?>
		<a title="<?php echo $brandLink?>" href="<?php echo $brandLink?>"><?php echo $brand?></a>
	<?php
	}

	function button( $goto, $keyword ) { 
		$title = $this->cleanDoubleQuote( $keyword );
	?>
		<a rel="nofollow" title="<?php echo $title?>" href="<?php echo $goto ?>">VISIT STORE</a>
	<?php
	}
	function cleanDoubleQuote( $str ) {
		return str_replace( '"', '', $str );
	}
}

trait SocialShare {
	function facebook( $permalink ) {
		return 'http://www.facebook.com/share.php?u=' . $permalink;
	}
	function twitter( $permalink ) {
		return 'http://twitter.com/share?text=' . $permalink;
	}
	function googlePlus( $permalink, $keyword ) {
		$title = $this->getTitle( $keyword );
		return 'http://www.google.com/bookmarks/mark?op=add&bkmk=' . $permalink . '&title=' . $title;
	}
	function pinterest( $permalink, $keyword ) {
		$title = $this->getTitle( $keyword );
		return 'http://pinterest.com/pin/create/button?url=' . $permalink . '&amp;title=' . $title;

	}
	function getTitle( $keyword ) {
		return  rawurlencode( $keyword );
	}
}


