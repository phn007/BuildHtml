<?php
class ClsHeader {
	function createHtml( $data = null ) {
	?>
		<header>
			<?php $this->siteName()?>
		</header>
		<?php $this->navigation()?>
	<?php
	}

	function navigation() {
		$cat = new CatMenuList_Plugin();
		$mnList = $cat->menuList();
	?>
		<div class="navigation">
            <div class="navigation-wrapper">
                <a href="javascript:void(0)" class="navigation-menu-button" id="js-mobile-menu"><span class="icon fa fa-navicon"></span></a>
                <nav role="navigation">
                    <ul id="js-navigation-menu" class="navigation-menu show">
                    	<li class="nav-link"><a href="<?php echo HOME_URL?>categories<?php echo FORMAT?>">CATEGORIES</a></li>
	            		<li class="nav-link"><a href="<?php echo HOME_URL?>brands<?php echo FORMAT?>">BRANDS</a></li>
                        <?php foreach ( $mnList as $name => $link ): ?>
							<li class="nav-link"><a href="<?php echo $link?>"><?php echo strtoupper( $name )?></a></li>
                        <?php endforeach ?>
                    </ul>
                </nav>
            </div>
        </div>
    <?php
	}

	function siteName() {
		echo '<a href="' . HOME_LINK . '">' . strtoupper( SITE_NAME) . '</a>';
	}
}