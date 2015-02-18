<?php
namespace T3DD\Frontend\Routing;

/**
 * Enter descriptions here
 */
class AppRouteHandler extends \TYPO3\Flow\Mvc\Routing\DynamicRoutePart {

	/**
	 * @var array
	 */
	protected $routes = array();

	/**
	 *
	 */
	protected function loadRoutes() {
		if (!empty($this->routes)) {
			return;
		}
		$appIndex = file_get_contents('resource://T3DD.Frontend/Public/app/index.html');
		$appRoutes = array();
		preg_match_all('!(<app-route\s.*path="(.+?)"\s?.*?>)!im', $appIndex, $appRoutes);
		if (isset($appRoutes[2]) && !empty($appRoutes[2])) {
			$this->routes = $appRoutes[2];
		}

	}

	/**
	 * @param string $routePath
	 * @return string
	 */
	protected function findValueToMatch($routePath) {
		$this->loadRoutes();
		$requestPath = '/' . $routePath;
		foreach ($this->routes as $route) {
			if ($this->testRoute($route, $requestPath)) {
				return $routePath;
			}
		}
		return false;
	}


	/**
	 * Checks whether the current URI section matches the configured RegEx pattern.
	 *
	 * @param string $requestPath value to match, the string to be checked
	 * @return boolean TRUE if value could be matched successfully, otherwise FALSE.
	 */
	protected function matchValue($requestPath) {
		if ($requestPath !== false) {
			return true;
		}
		return false;
	}

	/**
	 * Checks whether the route part matches the configured RegEx pattern.
	 *
	 * @param string $value The route part (must be a string)
	 * @return boolean TRUE if value could be resolved successfully, otherwise FALSE.
	 */
	protected function resolveValue($value) {
		return true;
	}

	/**
	 * @param string $routePath
	 * @param string $requestPath
	 * @return bool
	 */
	protected function testRoute($routePath, $requestPath) {
		// if the requestPath is an exact match or '*' then the route is a match
		if ($routePath === $requestPath || $routePath === '*') {
			return true;
		}

		// look for wildcards
		if (strpos($routePath, '*') === FALSE && strpos($routePath, ':') === FALSE) {
			// no wildcards and we already made sure it wasn't an exact match so the test fails
			return false;
		}

		// example requestPathSegments = ['', example', 'path']
		$requestPathSegments = explode('/', $requestPath);

		// example routePathSegments = ['', 'example', '*']
		$routePathSegments = explode('/', $routePath);

		// there must be the same number of path segments or it isn't a match
		if (count($requestPathSegments) !== count($routePathSegments)) {
			return false;
		}

		// check equality of each path segment
		$routePathSegmentsCount = count($routePathSegments);
		for ($i = 0; $i < $routePathSegmentsCount; $i++) {
		  // the path segments must be equal, be a wildcard segment '*', or be a path parameter like ':id'
		  $routeSegment = $routePathSegments[$i];
		  if ($routeSegment !== $requestPathSegments[$i] && $routeSegment !== '*' && $routeSegment[0] !== ':') {
			// the path segment wasn't the same string and it wasn't a wildcard or parameter
			return false;
		  }
		}

		return true;
	}

}