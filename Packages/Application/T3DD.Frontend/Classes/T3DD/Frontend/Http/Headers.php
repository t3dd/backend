<?php
namespace T3DD\Frontend\Http;

/**
 * This headers only fixes a nasty flow exception when a header HTTPS is sent and flow assumes it to contain
 * the status code.
 */
class Headers extends \TYPO3\Flow\Http\Headers {

	/**
	 * Creates a new Headers instance from the given $_SERVER-superglobal-like array.
	 *
	 * @param array $server An array similar or equal to $_SERVER, containing headers in the form of "HTTP_FOO_BAR"
	 * @return \TYPO3\Flow\Http\Headers
	 */
	static public function createFromServer(array $server) {
		$headerFields = array();
		if (isset($server['PHP_AUTH_USER']) && isset($server['PHP_AUTH_PW'])) {
			$headerFields['Authorization'] = 'Basic ' . base64_encode($server['PHP_AUTH_USER'] . ':' . $server['PHP_AUTH_PW']);
		}

		foreach ($server as $name => $value) {
			if (strpos($name, 'HTTP_') === 0) {
				$name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
				if (strtoupper($name) !== 'HTTPS') {
					//@todo T3DD Team, remove this condition once flow fixes handling of the HTTPS header
					$headerFields[$name] = $value;
				}
			} elseif ($name == 'REDIRECT_REMOTE_AUTHORIZATION' && !isset($headerFields['Authorization'])) {
				$headerFields['Authorization'] = $value;
			} elseif (in_array($name, array('CONTENT_TYPE', 'CONTENT_LENGTH'))) {
				$name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $name))));
				$headerFields[$name] = $value;
			}
		}
		return new self($headerFields);
	}

}