<?php
namespace T3DD\Login\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow framework.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * An action controller for generic authentication in Flow
 *
 * @Flow\Scope("singleton")
 * @deprecated since 1.2 Instead you should inherit from the AbstractAuthenticationController from within your package
 */
class AuthenticationController extends \TYPO3\Flow\Security\Authentication\Controller\AbstractAuthenticationController {

	/**
	 * The authentication manager
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 * @Flow\Inject
	 */
	protected $authenticationManager;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @var string
	 */
	protected $requestIDProtocol = 'requestID://';

	/**
	 */
	public function loginAction() {
		$requestID = $this->getReturnTo();
		$account = $this->securityContext->getAccount();
		if (is_object($account)) {
			$this->redirect('callback', NULL, NULL, array('requestID' => $requestID));
		}
		$ssoURL = 'https://typo3.org/my-account/sso/t3dd15/';
		if (!empty($requestID)) {
			$ssoURL .= '?returnTo=' . urlencode($this->requestIDProtocol . $requestID);
		}
		$this->redirectToUri($ssoURL);
	}

	/**
	 *
	 */
	public function statusAction() {
		$account = $this->securityContext->getAccount();
		if (is_object($account)) {
			$this->response->setHeader('Content-Type', 'application/json', TRUE);
			$this->response->setContent(json_encode($this->buildAccountDTO($this->securityContext->getAccount(), $this->response->getCookie('TYPO3_Flow_Session'))));
			return;
		}
		$this->response->setStatus(401, 'Not logged in.');
	}

	/**
	 * @param string $requestID
	 * @return string
	 */
	public function callbackAction($requestID) {
		$requestID = $this->sanitizeRequestID($requestID);
		return sprintf('<html><body>
			<script>
				window.opener.onSSOAuth("%s", %s);
				window.close();
			</script>
			</body></html>', $requestID, json_encode($this->buildAccountDTO($this->securityContext->getAccount(), $this->response->getCookie('TYPO3_Flow_Session'))));
	}

	/**
	 * Redirects to a potentially intercepted request. Returns an error message if there has been none.
	 *
	 * @param \TYPO3\Flow\Mvc\ActionRequest $originalRequest The request that was intercepted by the security framework, NULL if there was none
	 * @return string
	 */
	protected function onAuthenticationSuccess(\TYPO3\Flow\Mvc\ActionRequest $originalRequest = NULL) {
		if ($originalRequest !== NULL) {
			$this->redirectToRequest($originalRequest);
		}
		$requestID = $this->getReturnTo();
		if (!empty($requestID)) {
			$this->redirect('callback', NULL, NULL, array('requestID' => $requestID));
		}
		return '';
	}

	/**
	 * @return string
	 */
	protected function getReturnTo() {
		$requestID = isset($_REQUEST['returnTo']) ? $_REQUEST['returnTo'] : '';
		if (strncmp($requestID, $this->requestIDProtocol, strlen($this->requestIDProtocol)) === 0) {
			$requestID = substr($requestID, strlen($this->requestIDProtocol));
		}
		return $this->sanitizeRequestID($requestID);
	}

	/**
	 * @param string $requestID
	 * @return mixed
	 */
	protected function sanitizeRequestID($requestID) {
		return preg_replace('/[^abcdefghijklmnopqrstuvwxyz0123456789]/', '', $requestID);
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param \TYPO3\Flow\Http\Cookie $sessionCookie
	 * @return \stdClass
	 */
	protected function buildAccountDTO(\TYPO3\Flow\Security\Account $account, \TYPO3\Flow\Http\Cookie $sessionCookie = NULL) {
		/** @var \TYPO3\Party\Domain\Model\Person $person */
		$person = $account->getParty();
		$simpleAccount = new \stdClass();
		$simpleAccount->displayName = (string) $person->getName();
		if ($sessionCookie !== NULL) {
			$simpleAccount->sessionIdentifier = $sessionCookie->getValue();
		}
		$simpleAccount->profile = sprintf('http://typo3.org/services/userimage.php?username=%s&size=big', $account->getAccountIdentifier());
		return $simpleAccount;
	}
}
