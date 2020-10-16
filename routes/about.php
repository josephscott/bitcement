<?php
echo $app->use( 'twig' )->render( 'header.html' );
echo "<h1>About</h1>";
echo $app->use( 'twig' )->render( 'footer.html' );
