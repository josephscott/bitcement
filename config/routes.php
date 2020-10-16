<?php
$app->route( 'GET', '/', APP_DIR . '/routes/index.php' );

$app->route( 'GET', '/about', APP_DIR . '/routes/about.php' );
$app->route( 'POST', '/about', APP_DIR . '/routes/about.php' );
