<?php
declare( strict_types = 1 );

define( 'APP_DIR', __DIR__ );
require APP_DIR . '/vender/autoload.php';

$app = new BitCement\App();
require APP_DIR . '/config/boot.php';
