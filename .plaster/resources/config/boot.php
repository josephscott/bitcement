<?php
declare( strict_types = 1 );

foreach ( glob( __DIR__ . '/*.php' ) as $file ) {
	require $file;
}
