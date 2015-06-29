<?php
namespace T3DD\Frontend\Http;

/**
 * This request handler only fixes a nasty flow exception when a header HTTPS is sent and flow assumes it to contain
 * the status code.
 */
class RequestHandler extends \TYPO3\Flow\Http\RequestHandler {

	/**
	 * @return int One - Oh - One
	 */
	public function getPriority() {
		return 101;
	}

	/**
	 * Handles a HTTP request
	 *
	 * @return void
	 */
	public function handleRequest() {
		// Create the request very early so the Resource Management has a chance to grab it:
		$this->request = Request::createFromEnvironment();
		$this->response = new \TYPO3\Flow\Http\Response();

		$this->boot();
		$this->resolveDependencies();
		if (isset($this->settings['http']['baseUri'])) {
			$this->request->setBaseUri(new \TYPO3\Flow\Http\Uri($this->settings['http']['baseUri']));
		}

		$componentContext = new \TYPO3\Flow\Http\Component\ComponentContext($this->request, $this->response);
		$this->baseComponentChain->handle($componentContext);

		$this->response->send();

		$this->bootstrap->shutdown(\TYPO3\Flow\Core\Bootstrap::RUNLEVEL_RUNTIME);
		$this->exit->__invoke();
	}

}