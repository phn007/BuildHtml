<?php
class ClsProductDetail {
	use SocialShare;

	function createHtml( $data ) {
	?>
		<div id="product-detail-image">
			<?php $this->image( $data['detail'] )?>
		</div>
		<div id="product-detail-info">
			<?php $this->infomation( $data )?>
		</div>
	<?php
	}

	function image( $data ) {
		extract( $data );
		$title = $this->_cleanDoubleQuote( $keyword );
	?>
		<a rel="nofollow" title="<?php echo $title?>" href="<?php echo $goto?>">
			<img src="<?php echo $image_url ?>" alt="<?php echo $title?>">
		</a>
	<?php
	}

	function infomation( $data ) {
		extract( $data['detail'] );
	?>
		<h2><?php $this->_productName( $goto, $keyword  )?></h2>
		<p id="price"><span id="amount">$<?php echo $price?></span></p>
		<hr>
		<p id="description"><?php $this->_description( $description, $data['spin'] )?></p>
		<hr id="hr-desc">
		<div id="button"><span id="btn-visit"><?php $this->_button( $goto, $keyword )?></span></div>
		<div id="category">Category: <?php $this->_category( $category, $categoryLink )?></div>
		<div id="brand">Brand: <?php $this->_brand( $brand, $brandLink )?></div>
		<div id="merchant">Merchant: <?php $this->_merchant( $merchant, $goto )?></div>
		<div id="product-detail-social"><?php $this->_social( $permalink, $keyword )?></div>
	<?php
	}

	function _productName( $goto, $keyword ) {
		$title = $this->_cleanDoubleQuote( $keyword );
		echo '<a rel="nofollow" title="' . $title . '" href="' . $goto . '">' . $keyword . '</a>';
	}

	function _description( $description, $spin ) {
	?>
		<ul class="accordion-tabs">
		<li class="tab-header-and-content">
			<a href="javascript:void(0)" class="is-active tab-link">Product Info</a>
			<div class="tab-content">
				<p><?php echo $description?></p>
			</div>
		</li>
		<li class="tab-header-and-content">
			<a href="javascript:void(0)" class="tab-link">Tap1</a>
			<div class="tab-content">
				<h3><?php echo $spin['title1']?></h3>
				<p><?php echo $spin['ad1']?></p>
				<p><?php echo $spin['more_info1']?></p>
			</div>
		</li>
		<li class="tab-header-and-content">
			<a href="javascript:void(0)" class="tab-link">Tap2</a>
			<div class="tab-content">
				<h3><?php echo $spin['title2']?></h3>
				<p><?php echo $spin['ad2']?></p>
				<p><?php echo $spin['more_info2']?></p>
			</div>
		</li>
		<li class="tab-header-and-content">
			<a href="javascript:void(0)" class="tab-link">Tap3</a>
			<div class="tab-content">
				<p><?php echo $spin['ad_desc']?></p>
			</div>
		</li>
	</ul>
	<?php
	}

	function _button( $goto, $keyword ) {
		$title = $this->_cleanDoubleQuote( $keyword );
		echo '<a rel="nofollow" title="' . $title . '" href="' . $goto . '">VISIT STORE</a>';
	}

	function _brand( $brand, $brandLink ) {
		echo '<a title="' . $brand . '" href="' . $brandLink . FORMAT . '">' . $brand . '</a>';
	}

	function _category( $category, $categoryLink ) {
		echo '<a title="' . $category . '" href="' . $categoryLink . FORMAT . '">' . ucwords( $category ) . '</a>';
	}

	function _merchant( $merchant, $goto ) {
		echo '<a title="' . $merchant . '" href="' . $goto . '">' . $merchant . '</a>';
	}

	function _cleanDoubleQuote( $str ) {
		return str_replace( '"', '', $str );
	}

	function _social( $permalink, $keyword ) {
	?>

		<ul> Share With: 
			<li>
				<a rel="nofollow" href="<?php echo $this->facebook( $permalink )?>"><i class='icon fa fa-facebook'></i></a>
			</li>
			<li>
				<a rel="nofollow" href="<?php echo $this->twitter( $permalink )?>"><i class='icon fa fa-twitter'></i></a>
			</li>
			<li>
				<a rel="nofollow" href="<?php echo $this->googlePlus( $permalink, $keyword )?>"><i class='icon fa fa-google-plus'></i></a>
			</li>
			<li>
				<a rel="nofollow" href="<?php echo $this->pinterest( $permalink, $keyword )?>"><i class='icon fa fa-pinterest'></i></a>
			</li>
		</ul>
	<?php
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