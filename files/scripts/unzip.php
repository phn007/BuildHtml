<?php
foreach ($_POST as $file) {
	$open_path    = $file['open_path'];
	$target_path  = $file['target_path'];
	$unzip_result = unzip( $open_path, $target_path );
}

//displayFileAndDirectory();

function unzip( $open_path, $target_path ) {
	$zip = new ZipArchive(); # extension=php_zip.dll - php.ini
	$x = $zip->open( $open_path );

	if ( $x !== true ) 
		die( 'There was a problem. Please try again! ---> ' . $open_path );

	$zip->extractTo( $target_path );
	$zip->close();
	unlink( $open_path ); #deletes the zip file. We no longer need it.
}

// function displayFileAndDirectory() {
// 	if ( $handle = opendir('.') ) {
// 		while ( false !== ( $entry = readdir( $handle ) ) ) {
// 			if ( $entry != "." && $entry != "..") {
// 			    echo "$entry\n";
// 			}
// 		}
// 		closedir($handle);
// 	}
// }