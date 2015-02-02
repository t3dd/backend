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
		$ssoURL = 'https://typo3.org/my-account/sso/t3dd15/';
		$requestID = $this->getReturnTo();
		if (!empty($requestID)) {
			$ssoURL .= '?returnTo=' . urlencode($this->requestIDProtocol . $requestID);
		}
		$this->redirectToUri($ssoURL);
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
			return sprintf('<html><body>
			<script>
				window.opener.onSSOAuth("%s", %s);
				window.close();
			</script>
			</body></html>', $requestID, json_encode($this->buildAccountDTO($this->securityContext->getAccount())));
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
		return preg_replace('/[^ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789]/', '', $requestID);
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 * @return \stdClass
	 */
	protected function buildAccountDTO(\TYPO3\Flow\Security\Account $account) {
		/** @var \TYPO3\Party\Domain\Model\Person $person */
		$person = $account->getParty();
		$simpleAccount = new \stdClass();
		$simpleAccount->displayName = (string) $person->getName();
		return $simpleAccount;
	}
}
