<?php
use webtools\libs\Helper;
trait Logo {
	use CreateImage;

	function createLogo() {
		$this->createLogoImage();
		$this->printCreateLogoResult();
	}

	function logoDestination() {
		$path = TEXTSITE_PATH . $this->config['project'] . '/' . $this->config['site_dir'];
		return $path . '/images/logo/';
	}

	function logoText() {
		return $this->config['site_name'];
	}

	function logoFontPath() {
		return SITE_CREATOR_PATH . 'textsite/images/logo/';
	}

	function logoBgColor() {
		return $this->config['logo_bg_color'];
	}

	function printCreateLogoResult() {
		echo $this->logoDestination() . ": Created image logo  done...\n";
	}
}


trait CreateImage {
	function createLogoImage() {
		//กำหนดตัวแปร
		$dest = $this->logoDestination();
		$logo_text = $this->logoText();
		$font_logo_path = $this->logoFontPath();
		$logo_bg_color = $this->logoBgColor();


		Helper::make_dir( $dest );

		$img_size = explode( ',', '350,30' );
		$text_color = explode( ',', '255, 255, 255' );
		//$bg_color = explode( ',', '231, 76, 60' );
		$bg_color = explode( ',', $logo_bg_color );
		$font_size = '20';
		$pos = explode( ',', '0, 0, 20' );


		//creates a image handle
		//$img = imagecreate( 500, 200 );
		$img = imagecreate( $img_size[0], $img_size[1] );

		//choose a bg color, u can play with the rgb values
		//$background = imagecolorallocate( $img, 232, 0, 135 );
		$background = imagecolorallocate( $img, $bg_color[0], $bg_color[1], $bg_color[2] );

		//chooses the text colors
		//$text_colour = imagecolorallocate( $img, 255, 255, 255 );
		$text_colour = imagecolorallocate( $img, $text_color[0], $text_color[1], $text_color[2] );

		//sets the thickness/bolness of the line
		imagesetthickness ( $img, 3 );

		//draws a line  params are (imgres,x1,y1,x2,y2,color)
		//imageline( $img, 20, 130, 165, 130, $text_colour );


		// place the font file in the same dir level as the php file
		$font = $font_logo_path . 'arialuni.ttf';



		//this function sets the font size, places to the co-ords
		//imagettftext($img, 100, 0, 11, 120, $text_colour, $font, $text);
		imagettftext ($img, $font_size, $pos[0], $pos[1], $pos[2], $text_colour, $font, $logo_text);

		//places another text with smaller size
		//imagettftext($img, 20, 0, 10, 160, $text_colour, $font, 'Small Text');

		//alerts the browser abt the type of content i.e. png image
		//header( 'Content-type: image/png' );
		//now creates the image
		//imagepng( $img );

		//create and save
		imagepng( $img, $dest . 'logo.png' );

		//destroys used resources
		//imagecolordeallocate( $text_color );
		//imagecolordeallocate( $background );
		imagedestroy( $img );
	}
}