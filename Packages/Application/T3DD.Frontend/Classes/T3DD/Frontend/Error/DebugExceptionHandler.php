<?php
namespace T3DD\Frontend\Error;

/**
 * Enter descriptions here
 */
class DebugExceptionHandler extends \TYPO3\Flow\Error\DebugExceptionHandler {

	/**
	 * @var array
	 */
	protected $supportedMediaTypes = array(
		'application/json'
	);

	/**
	 * @param \TYPO3\Flow\Core\Bootstrap
	 */
	public function injectBootstrap(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
		$this->bootstrap = $bootstrap;
	}

	/**
	 * @param int $statusCode
	 * @param \Exception $exception
	 * @return string
	 */
	protected function renderStatically($statusCode, \Exception $exception) {
		$requestMediaType = $this->getRequestMediaType();
		if ($requestMediaType === 'application/json') {
			return $this->renderStaticallyJson($statusCode, $exception);
		} else {
			return parent::renderStatically($statusCode, $exception);
		}
	}

	/**
	 * Returns the statically rendered exception message
	 *
	 * @param integer $statusCode
	 * @param \Exception $exception
	 * @return string
	 */
	protected function renderStaticallyJson($statusCode, \Exception $exception) {
		$errors = array();

		while (TRUE) {
			$pathPosition = strpos($exception->getFile(), 'Packages/');
			$filePathAndName = ($pathPosition !== FALSE) ? substr($exception->getFile(), $pathPosition) : $exception->getFile();

			$exceptionMessageParts = $this->splitExceptionMessage($exception->getMessage());

			$error = array(
				'code' => $exception->getCode() > 0 ? $exception->getCode() : '',
				'message' => $exceptionMessageParts['subject'] . ($exception->getCode() > 0 ? ', Code: ' . $exception->getCode() : ''),
				'source' => sprintf('%s thrown in %s on line %s', get_class($exception), $filePathAndName, $exception->getLine())
			);

			$errors[] = $error;

			if ($exception->getPrevious() === NULL) {
				break;
			}

			$exception = $exception->getPrevious();
		}

		$result = array(
			'errors' => array('system' => $errors),
		);

		header('Content-Type: application/json');
		return json_encode($result);
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