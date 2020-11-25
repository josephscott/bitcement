<?php
declare( strict_types = 1 );

require realpath( __DIR__ . '/../init.php' );

session_start();
$app->run();
