<?php
declare( strict_types = 1 );

require realpath( __DIR__ . '/../init.php' );

// Routes
$app->route( 'GET', '/', APP_DIR . '/routes/home.php' );

$app->route( 'GET', '/about', APP_DIR . '/routes/about.php' );
$app->route( 'POST', '/about', APP_DIR . '/routes/about.php' );

$app->run();
