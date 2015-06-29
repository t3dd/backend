<?php
namespace T3DD\Frontend;

/**
 * Enter descriptions here
 */
class Package extends \TYPO3\Flow\Package\Package {

	/**
	 * @param \TYPO3\Flow\Core\Bootstrap $bootstrap
	 */
	public function boot(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
		$bootstrap->registerRequestHandler(new Http\RequestHandler($bootstrap));
	}

}