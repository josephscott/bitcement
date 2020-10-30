<?php
declare( strict_types = 1 );

use \ParagonIE\AntiCSRF\AntiCRSF;

if ( !defined( 'APP_TWIG_AUTO_RELOAD' ) ) {
	define( 'APP_TWIG_AUTO_RELOAD', true );
}

$app->inject(
	'twig',
	new \Twig\Environment(
		new \Twig\Loader\FilesystemLoader( APP_DIR . '/templates' ),
		[
			'cache' => APP_DIR . '/cache/twig',
			'auto_reload' => APP_TWIG_AUTO_RELOAD
		]
	)
);

$app->use( 'twig' )->addFunction(
	new \Twig\TwigFunction(
		'form_token',
		function( $lock_to = null ) {
			static $csrf;

			if ( $csrf === null ) {
				$csrf = new AntiCRSF();
			}

			return $csrf->insertToken( $lock_to, false );
		},
		['is_safe' => ['html']]
	)
);
