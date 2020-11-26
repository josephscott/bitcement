<?php
declare( strict_types = 1 );

$out =<<<HTML

<div class="row">
	<div class="col-md">
		<h1>Hello World!</h1>
	</div>
</div>

HTML;

echo $app->use( 'twig' )->render( 'header.html' );
echo $out;
echo $app->use( 'twig' )->render( 'footer.html' );
