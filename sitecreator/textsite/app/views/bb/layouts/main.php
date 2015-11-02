<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php echo $seoTags?>
	<link rel="stylesheet" href="<?php echo CSS_PATH?>application.css">
</head>
<body>
	<div class="page-wrap">
		<header role="banner">
			<?php if ( isset( $header ) ) echo $header; ?>
		</header>
		<div id="main-content">
			[%LAYOUT_CONTENT%]
		</div>
	</div>
	<footer class="site-footer">
		<?php if ( isset( $footer ) ) echo $footer; ?>
	</footer>
	<script src="<?php echo JS_PATH?>plugins.js"></script>
	<script src="<?php echo JS_PATH?>main.js"></script>

	<!-- Start of StatCounter -->
    <script type="text/javascript">
      var sc_project=<?php echo SC_PROJECT ?>;
      var sc_invisible=1;
      var sc_security="<?php echo SC_SECURITY ?>";
      var scJsHost = (("https:" == document.location.protocol) ? "https://secure." : "http://www.");
      document.write("<sc"+"ript type='text/javascript' src='" + scJsHost + "statcounter.com/counter/counter.js'></"+"script>");
    </script>
    <noscript>
     <div class="statcounter">
       <a title="websitestatistics" href="http://statcounter.com/free-web-stats/"
        target="_blank"><img class="statcounter"
        src="http://c.statcounter.com/<?php echo SC_PROJECT ?>/0/<?php echo SC_SECURITY ?>/1/"
        alt="website statistics">
        </a>
      </div>
    </noscript>
    <!-- End of StatCounter -->
    
</body>
</html>