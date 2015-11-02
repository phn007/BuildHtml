<?php
$params = array();

if ( isset( $argv[1] ) ) $projectName = $argv[1];
if ( isset( $argv[2] ) ) $siteDirName = $argv[2];
if ( isset( $argv[3] ) ) $controller = $argv[3];
if ( isset( $argv[4] ) ) $action = $argv[4];
if ( isset( $argv[5] ) ) $params[0] = $argv[5];
if ( isset( $argv[6] ) ) $params[1] = $argv[6];

$dir = '../textsite/';
$textsiteDir = $dir . $projectName . '/' . $siteDirName . '/';

$configPath = $textsiteDir . 'config/config.php';
if ( ! file_exists( $configPath ) )
	die( "config file does not exitst" );
include $configPath;

extract( $cfg );
$scArr = explode( '#', $statcounter );
$sc_project = $scArr[0];
$sc_security = $scArr[1];

include $textsiteDir . 'config/define-site-config.php';
include $textsiteDir . 'libs/initvars.php';
include $textsiteDir . 'libs/object.php';
include $textsiteDir . 'libs/controller.php';
include $textsiteDir . 'libs/component.php';
include $textsiteDir . 'libs/cache.php';
include $textsiteDir . 'libs/helper.php';

include 'router.php';
Map::dispatch( $controller, $action, $params );

