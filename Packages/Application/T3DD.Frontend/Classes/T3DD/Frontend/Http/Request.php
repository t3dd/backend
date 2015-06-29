<?php
namespace T3DD\Frontend\Http;

/**
 * This request only fixes a nasty flow exception when a header HTTPS is sent and flow assumes it to contain
 * the status code.
 */
class Request extends \TYPO3\Flow\Http\Request {

	/**
	 * Constructs a new Request object based on the given environment data.
	 *
	 * @param array $get Data similar to that which is typically provided by $_GET
	 * @param array $post Data similar to that which is typically provided by $_POST
	 * @param array $files Data similar to that which is typically provided by $_FILES
	 * @param array $server Data similar to that which is typically provided by $_SERVER
	 * @see create()
	 * @see createFromEnvironment()
	 * @api
	 */
	public function __construct(array $get, array $post, array $files, array $server) {
		$this->headers = Headers::createFromServer($server);
		$method = isset($server['REQUEST_METHOD']) ? $server['REQUEST_METHOD'] : 'GET';
		if ($method === 'POST') {
			if (isset($post['__method'])) {
				$method = $post['__method'];
			} elseif (isset($server['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
				$method = $server['HTTP_X_HTTP_METHOD_OVERRIDE'];
			} elseif (isset($server['HTTP_X_HTTP_METHOD'])) {
				$method = $server['HTTP_X_HTTP_METHOD'];
			}
		}
		$this->setMethod($method);

		if ($this->headers->has('X-Forwarded-Proto')) {
			$protocol = $this->headers->get('X-Forwarded-Proto');
		} else {
			$protocol = isset($server['SSL_SESSION_ID']) || (isset($server['HTTPS']) && ($server['HTTPS'] === 'on' || strcmp($server['HTTPS'], '1') === 0)) ? 'https' : 'http';
		}
		$host = isset($server['HTTP_HOST']) ? $server['HTTP_HOST'] : 'localhost';
		$requestUri = isset($server['REQUEST_URI']) ? $server['REQUEST_URI'] : '/';
		if (substr($requestUri, 0, 10) === '/index.php') {
			$requestUri = '/' . ltrim(substr($requestUri, 10), '/');
		}
		$this->uri = new \TYPO3\Flow\Http\Uri($protocol . '://' . $host . $requestUri);

		if ($this->headers->has('X-Forwarded-Port')) {
			$this->uri->setPort($this->headers->get('X-Forwarded-Port'));
		} elseif (isset($server['SERVER_PORT'])) {
			$this->uri->setPort($server['SERVER_PORT']);
		}

		$this->server = $server;
		$this->arguments = $this->buildUnifiedArguments($get, $post, $files);
	}

}