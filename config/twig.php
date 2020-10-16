<?php
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
