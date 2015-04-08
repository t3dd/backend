<?php
namespace T3DD\Frontend\Error;

/**
 * Enter descriptions here
 */
class ProductionExceptionHandler extends \TYPO3\Flow\Error\ProductionExceptionHandler {

	/**
	 * @var array
	 */
	protected $supportedMediaTypes = array(
		'application/json'
	);

	/**
	 * @param int $statusCode
	 * @param string $referenceCode
	 */
	protected function renderStatically($statusCode, $referenceCode) {
		$requestMediaType = $this->getRequestMediaType();
		if ($requestMediaType === 'application/json') {
			$this->renderStaticallyJson($statusCode, $referenceCode);
		} else {
			parent::renderStatically($statusCode, $referenceCode);
		}
	}

	/**
	 * @param int $statusCode
	 * @param string $referenceCode
	 */
	protected function renderStaticallyJson($statusCode, $referenceCode) {
		header('Content-Type: application/json');
		$result = array(
			'message' => 'An internal error occurred.'
		);
		if ($referenceCode !== NULL) {
			$result['message'] .= 'When contacting the maintainer of this application please mention the following reference code: ' . $referenceCode;
		}
		echo json_encode($result);
	}

	/**
	 * @return null|string
	 */
	protected function getRequestMediaType(){
		if (PHP_SAPI !== 'cli' && $httpRequest = \TYPO3\Flow\Http\Request::createFromEnvironment()) {
			if ($httpRequest !== NULL) {
				return $httpRequest->getNegotiatedMediaType($this->supportedMediaTypes);
			}
		}
		return NULL;
	}

}