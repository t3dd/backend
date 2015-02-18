<?php
namespace T3DD\Frontend\Routing;

use TYPO3\Flow\Annotations as Flow;

/**
 * Enter descriptions here
 */
class AppRouteHandler extends \TYPO3\Flow\Mvc\Routing\AbstractRoutePart implements \TYPO3\Flow\Mvc\Routing\DynamicRoutePartInterface  {

	/**
	 * @var \TYPO3\Flow\Core\Bootstrap
	 * @Flow\Inject
	 */
	protected $bootstrap;

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

	/**
	 * Sets split string of the Route Part.
	 * The split string represents the border of a Dynamic Route Part.
	 * If it is empty, Route Part will be equal to the remaining request path.
	 *
	 * @param string $splitString
	 * @return void
	 * @api
	 */
	public function setSplitString($splitString) {
		return '';
	}

	/**
	 * Checks whether this Route Part corresponds to the given $routePath.
	 * This method does not only check if the Route Part matches. It can also
	 * shorten the $routePath by the matching substring when matching is successful.
	 * This is why $routePath has to be passed by reference.
	 *
	 * @param string &$routePath The request path to be matched - without query parameters, host and fragment.
	 * @return boolean TRUE if Route Part matched $routePath, otherwise FALSE.
	 */
	public function match(&$routePath) {
		$currentRequest = $this->getHttpRequest();
		if ($currentRequest === NULL) return FALSE;

		$mediaType = $currentRequest->getNegotiatedMediaType(array('text/html'));
		if ($mediaType === NULL) return FALSE;

		$this->loadRoutes();
		$requestPath = '/' . $routePath;
		foreach ($this->routes as $route) {
			if ($this->testRoute($route, $requestPath)) {
				$routePath = '';
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * Checks whether $routeValues contains elements which correspond to this Dynamic Route Part.
	 * If a corresponding element is found in $routeValues, this element is removed from the array.
	 *
	 * @param array $routeValues An array with key/value pairs to be resolved by Dynamic Route Parts.
	 * @return boolean TRUE if current Route Part could be resolved, otherwise FALSE
	 */
	public function resolve(array &$routeValues) {
		$this->value = NULL;
		if ($this->name === NULL || $this->name === '') {
			return FALSE;
		}
		$valueToResolve = $this->findValueToResolve($routeValues);
		if (!$this->resolveValue($valueToResolve)) {
			return FALSE;
		}
		$routeValues = Arrays::unsetValueByPath($routeValues, $this->name);
		return TRUE;
	}

	/**
	 * Returns the route value of the current route part.
	 * This method can be overridden by custom RoutePartHandlers to implement custom resolving mechanisms.
	 *
	 * @param array $routeValues An array with key/value pairs to be resolved by Dynamic Route Parts.
	 * @return string|array value to resolve.
	 * @api
	 */
	protected function findValueToResolve(array $routeValues) {
		return \TYPO3\Flow\Reflection\ObjectAccess::getPropertyPath($routeValues, $this->name);
	}

	/**
	 * Checks, whether given value can be resolved and if so, sets $this->value to the resolved value.
	 * If $value is empty, this method checks whether a default value exists.
	 * This method can be overridden by custom RoutePartHandlers to implement custom resolving mechanisms.
	 *
	 * @param string $value value to resolve
	 * @return boolean TRUE if value could be resolved successfully, otherwise FALSE.
	 * @api
	 */
	protected function resolveValue($value) {
		if ($value === NULL) {
			return FALSE;
		}
		if (is_object($value)) {
			$value = $this->persistenceManager->getIdentifierByObject($value);
			if ($value === NULL || !is_string($value)) {
				return FALSE;
			}
		}
		$this->value = urlencode($value);
		if ($this->lowerCase) {
			$this->value = strtolower($this->value);
		}
		return TRUE;
	}

	/**
	 * @return \TYPO3\Flow\Http\Request
	 */
	protected function getHttpRequest() {
		$requestHandler = $this->bootstrap->getActiveRequestHandler();
		if ($requestHandler instanceof \TYPO3\Flow\Http\HttpRequestHandlerInterface) {
			return $requestHandler->getHttpRequest();
		}
	}
}