<?php
class Footer {
	use FooterSocialShare;

	function createHtml( $footer ) {
	?>
		<div id="footer-tab">
            <div id="social">
                <ul class="social">
                    <li><a href="<?php echo $this->facebook()?>" title="Facebook"><span class="icon fa fa-facebook"></span></a></li>
                    <li><a href="<?php echo $this->twitter()?>" title="Twitter"><span class="icon fa fa-twitter"></span></a></li>
                    <li><a href="<?php echo $this->googlePlus()?>" title="Google+"><span class="icon fa fa-google-plus"></span></a></li>
                    <li><a href="<?php echo $this->pinterest()?>" title="Pinterest"><span class="icon fa fa-pinterest"></span></a></li>
                    <li><a href="<?php echo $this->linkedIn()?>" title="LinkedIn"><span class="icon fa fa-linkedin"></span></a></li>
                    <li><a href="<?php echo $this->stumbleupon()?>" title="Stumbleupon"><span class="icon fa fa-stumbleupon"></span></a></li>	
                </ul>
            </div>
        </div>
	    <div id="footer-container">
	        <div id="footer-left">
	        	<?php $this->productPageLink( $footer )?>
	        	<div>
	        		<?php $this->copyrightLink( $footer )?>
	        	</div>
	        </div>
	        <div id="footer-right">
				<ul>
		        	<li><a href="<?php echo HOME_URL?>sitemap<?php echo FORMAT?>">Sitemap</a></li>
		            <li><a href="<?php echo HOME_URL?>contact-us<?php echo FORMAT?>">About us</a></li>
		            <li><a href="<?php echo HOME_URL?>about-us<?php echo FORMAT?>">Contact us</a></li>
		            <li><a href="<?php echo HOME_URL?>privacy-policy<?php echo FORMAT?>">Privacy Policy</a></li>
	        	</ul>
	        </div>
	    </div>
	<?php
	}

	function getDomain() {
		$arr = explode( '/', rtrim( HOME_URL, '/' ) );
		return end( $arr );
	}

	function getYear() {
		date_default_timezone_set("Asia/Bangkok");
		return date("Y");
	}

	function productPageLink( $footer ) {
		if ( $footer['current-page'] == 'product-page' ): 
			extract( $footer['spin-content'] );
			extract( $footer['product-detail'] );
		?>
			<ul class="linkout">
				<li><a rel="nofollow" target="_blank" href="<?php echo $this->linkout( $keyword, $linkout1 )?>"><?php echo $keyword?></a></li>
				<li><a rel="nofollow" target="_blank" href="<?php echo $this->youtubeLinkOut( $keyword )?>">Youtube</a></li>
			</ul>
	<?php
		endif;
	}

	function copyrightLink( $footer ) {
	?>
		<a href="<?php echo HOME_URL?>">©<?php echo $this->getYear()?> <?php echo $this->getDomain()?> – All Rights Reserved</a>
	<?php
	}

	function linkout( $keyword, $linkout1 ) {
		return str_replace( '%anchor_text%', urlencode( Helper::getSearchKey( $keyword ) ), $linkout1 );
	}
	function youtubeLinkOut( $keyword ) {
		return 'https://www.youtube.com/results?search_query=' . urlencode( Helper::getSearchKey( $keyword ) );
	}

}

trait FooterSocialShare {
	function facebook() {
		return 'http://www.facebook.com/share.php?u=' . $this->url();
	}
	function twitter() {
		return 'http://twitter.com/share?text=' . $this->url();
	}
	function googlePlus() {
		return 'http://www.google.com/bookmarks/mark?op=add&bkmk=' . $this->url() . '&title=' . SITE_NAME;
	}
	function pinterest() {
		return 'http://pinterest.com/pin/create/button?url=' . $this->url() . '&amp;title=' . SITE_NAME;
	}
	function linkedIn() {
		return 'http://linkedin.com/shareArticle?mini=true&amp;url=' . $this->url() . '&amp;title=' . SITE_NAME;
	}
	function stumbleupon() {
		return 'http://www.stumbleupon.com/badge/?url=' . $this->url();
	}

	function url() {
		return rtrim( HOME_LINK, '/' );
	}
}