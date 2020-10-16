<?php
use \ParagonIE\AntiCSRF\AntiCSRF;

$app->inject(
	'twig',
	new \Twig\Environment(
		new \Twig\Loader\FilesystemLoader( APP_DIR . '/templates' ),
			[
				'cache' => APP_DIR . '/cache/twig',
				'auto_reload' => true
			]
	)
);

$app->use( 'twig' )->addFunction(
	new \Twig\TwigFunction(
		'form_token',
		function( $lock_to = null ) {
			static $csrf;
			if ( $csrf === null ) {
				$csrf = new AntiCSRF();
			}
			return $csrf->insertToken($lock_to, false);
		},
		['is_safe' => ['html']]
	)
);
