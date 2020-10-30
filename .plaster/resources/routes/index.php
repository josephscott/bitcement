<?php
echo $app->use( 'twig' )->render( 'header.html' );
echo "Hello World";
echo $app->use( 'twig' )->render( 'footer.html' );
