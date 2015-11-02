<?php
/*
 * PHP: Recursively Backup Files & Folders to ZIP-File
 * (c) 2012-2014: Marvin Menzerath - http://menzerath.eu
 * contribution: Drew Toddsby
*/

// Make sure the script can handle large folders/files
ini_set('max_execution_time', 600);
ini_set('memory_limit','1024M');

// Start the backup!
// zipData('/path/to/folder', '/path/to/backup.zip');
// echo 'Finished.';

// Here the magic happens :)
function zipData($source, $destination) {
	if (extension_loaded('zip')) {
		if (file_exists($source)) {
			$zip = new ZipArchive();
			if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
				$source = realpath($source);
				if (is_dir($source)) {
					$iterator = new RecursiveDirectoryIterator($source);
					// skip dot files while iterating 
					$iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
					$files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
					foreach ($files as $file) {
						$file = realpath($file);
						if (is_dir($file)) {
							$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
						} else if (is_file($file)) {
							$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
						}
					}
				} else if (is_file($source)) {
					$zip->addFromString(basename($source), file_get_contents($source));
				}
			}
			echo $destination . ' zip: successfully...';
			echo "\n";
			return $zip->close();
		}
	}
	return false;
}

function zipData1( $source, $destination ) {
	if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

	// Get real path for our folder
	$rootPath = realpath( $source );

	// Initialize archive object
	$zip = new ZipArchive();
	$zip->open( $destination, ZipArchive::CREATE | ZipArchive::OVERWRITE );

	// Create recursive directory iterator
	/** @var SplFileInfo[] $files */
	$files = new RecursiveIteratorIterator(
	    new RecursiveDirectoryIterator( $rootPath ),
	    RecursiveIteratorIterator::LEAVES_ONLY
	);

	foreach ( $files as $name => $file ) {
	    // Skip directories (they would be added automatically)
	    if (!$file->isDir()) {
	        // Get real and relative path for current file
	        $filePath = $file->getRealPath();
	        $relativePath = substr( $filePath, strlen( $rootPath ) + 1);

	        // Add current file to archive
	        $zip->addFile( $filePath, $relativePath );
	    }
	}
	// Zip archive will be created only after closing object
	$zip->close();

}