<?php
declare( strict_types = 1 );
namespace BitCement;

class App {
	private $routes;
	private $inject;
	private $handle_errors;

	public function __construct() {
		$this->routes = [];
		$this->inject = [];
		$this->handle_errors = [];

		$this->handle_error( 'not_found', function( $vars ) {
			echo "<h1>Not Found</h1>\n";
		} );

		$this->handle_error( 'method_not_allowed', function( $vars ) {
			echo "<h1>Method Not Allowed</h1>\n";
		} );
	}

	public function dispatch( $route_info, $uri ) {
		switch( $route_info[0] ) {
			case \FastRoute\Dispatcher::NOT_FOUND;
				// Redirect trailing slashes
				if ( substr( $uri, -1 ) === '/' ) {
					header( "Location: " . rtrim( $uri, '/' ), true, 301 );
					exit();
				}

				$this->hand_off( $this->handle_errors['not_found'], [] );
				break;
			case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
				$allowed_methods = $route_info[1];
				$this->hand_off(
					$this->handle_errors['method_not_allowed'],
					[]
				);
				break;
			case \FastRoute\Dispatcher::FOUND:
				$this->hand_off( $route_info[1], $route_info[2] );
				break;
		}

		exit();
	}

	public function hand_off( $handler, $vars ) {
		$app = $this;
		$file_route = function ( $file_path, $args ) use ( $app ) {
			require $file_path;
		};

		if ( is_callable( $handler ) ) {
			$handler( $vars );
		} elseif (
			is_string( $handler )
			&& substr( $handler, 0, 1 ) === '/'
			&& is_readable( $handler )
		) {
			$file_route( $handler, $vars );
		}

		exit();
	}

	// Possible error conditions:
	// - not_found
	// - method_not_allowed
	public function handle_error( $condition, $handler ) {
		$this->handle_errors[ $condition ] = $handler;
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
