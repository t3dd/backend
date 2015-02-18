<?php
namespace T3DD\Frontend\Aspects;

use TYPO3\Flow\Annotations as Flow;

/**
 * Enter descriptions here
 *
 * @Flow\Aspect
 */
class RouterCachingServiceAspect {

	/**
	 * @var string
	 */
	protected $defaultMediaType = 'text/html';

	/**
	 * @var array
	 */
	protected $allowedMediaTypes = array(
		'text/html',
		'application/json'
	);

	/**
	 * @param \TYPO3\Flow\Aop\JoinPoint $joinPoint
	 * @Flow\Around("method(TYPO3\Flow\Mvc\Routing\RouterCachingService->buildRouteCacheIdentifier())")
	 */
	public function buildRouteCacheIdentifierWithAcceptHeader(\TYPO3\Flow\Aop\JoinPoint $joinPoint) {
		/** @var \TYPO3\Flow\Http\Request $httpRequest */
		$httpRequest = $joinPoint->getMethodArgument('httpRequest');
		$mediaTypes = $httpRequest->getAcceptedMediaTypes();
		if (FALSE !== $wildcard =  array_search('*/*', $mediaTypes)) {
			$mediaTypes[$wildcard] = $this->defaultMediaType;
			$mediaTypes = array_unique($mediaTypes);
		}
		$commonTypes = array_intersect($this->allowedMediaTypes, $mediaTypes);
		return md5(sprintf('%s_%s_%s_%s', $httpRequest->getUri()->getHost(), $httpRequest->getRelativePath(), $httpRequest->getMethod(), implode('_', $commonTypes)));
	}

}