<?php
declare( strict_types = 1 );

define( 'APP_DIR', __DIR__ );
require APP_DIR . '/vendor/autoload.php';

$app = new BitCement\App();

foreach ( glob( APP_DIR . "/config/*.php" ) as $file ) {
	require $file;
}
