<?php
declare( strict_types = 1 );
namespace BitCement;

class App {
	private $routes;
	private $inject;

	public function __construct() {
		$this->routes = [];
		$this->inject = [];
	}

	public function dispatch( $route_info, $uri ) {
		$app = $this;
		$file_route = function( $file_path, $args ) use ( $app ) {
			require $file_path;
		};

		switch( $route_info[0] ) {
			case \FastRoute\Dispatcher::NOT_FOUND;
				// 404 Not Found

				// Redirect trailing slashes
				if ( substr( $uri, -1 ) === '/' ) {
					header( "Location: " . rtrim( $uri, '/' ), true, 301 );
					exit();
				}

				break;
			case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
				$allowed_methods = $route_info[1];
				// 405 Method Not Allowed
				break;
			case \FastRoute\Dispatcher::FOUND:
				$handler = $route_info[1];
				$vars = $route_info[2];

				if ( is_callable( $handler ) ) {
					$handler();
				} elseif (
					is_string( $handler )
					&& substr( $handler, 0, 1 ) === '/'
					&& is_readable( $handler )
				) {
					$file_route( $handler, $vars );
				}
				break;
		}

		exit();
	}

	public function inject( $name, $thing ) {
		$this->inject[$name] = $thing;
	}

	public function run() {
		$dispatcher = \FastRoute\cachedDispatcher(
			function( \FastRoute\RouteCollector $r ) {
				foreach( $this->routes as $method => $route ) {
					foreach( $route as $details ) {
						$r->addRoute( $method, $details['pattern'], $details['handler'] );
					}
				}
			}, [
				'cacheFile' => '/dev/null',
				'cacheDisabled' => true
			]
		);

		$http_method = $_SERVER['REQUEST_METHOD'];
		$uri = $_SERVER['REQUEST_URI'];
		$pos = strpos( $uri, '?' );
		if ( $pos !== false ) {
			$uri = substr( $uri, 0, $pos );
		}

		$uri = rawurldecode( $uri );
		$route_info = $dispatcher->dispatch( $http_method, $uri );

		$this->dispatch( $route_info, $uri );
	}

	public function route( string $method, string $pattern, $handler ) {
		$this->routes[$method][] = [
			'pattern' => $pattern,
			'handler' => $handler
		];
	}

	public function use( $name ) {
		return $this->inject[$name];
	}
}
