<?php
namespace T3DD\Login\Security\Authentication\Provider;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Account;
use TYPO3\Flow\Security\Authentication\Token\Typo3OrgSsoToken;
use TYPO3\Flow\Security\Authentication\TokenInterface;
use TYPO3\Flow\Security\Exception\UnsupportedAuthenticationTokenException;
use TYPO3\Party\Domain\Model\ElectronicAddress;
use TYPO3\Party\Domain\Model\Person;
use TYPO3\Party\Domain\Model\PersonName;

/**
 * Enter descriptions here
 */
class Typo3OrgSsoProvider extends \TYPO3\Flow\Security\Authentication\Provider\Typo3OrgSsoProvider {

	/**
	 * @var \TYPO3\Party\Domain\Repository\PartyRepository
	 * @Flow\Inject
	 */
	protected $partyRepository;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * @var \TYPO3\Flow\Security\Policy\RoleRepository
	 * @Flow\Inject
	 */
	protected $roleRepository;

	/**
	 * @param TokenInterface $authenticationToken
	 * @throws \TYPO3\Flow\Security\Exception\UnsupportedAuthenticationTokenException
	 */
	public function authenticate(TokenInterface $authenticationToken) {
		if (!($authenticationToken instanceof Typo3OrgSsoToken)) {
			throw new UnsupportedAuthenticationTokenException('This provider cannot authenticate the given token.', 1217339840);
		}

		/** @var $account Account */
		$account = null;
		$credentials = $authenticationToken->getCredentials();

		if (is_array($credentials) && isset($credentials['username'])) {
			$providerName = $this->name;
			$this->securityContext->withoutAuthorizationChecks(
				function () use ($credentials, $providerName, &$account) {
					$account = $this->accountRepository->findActiveByAccountIdentifierAndAuthenticationProviderName(
						$credentials['username'],
						$providerName
					);
				}
			);
		}

		$authenticationData = 'version=' . $credentials['version'] .
			'&user=' . $credentials['username'] .
			'&tpa_id=' . $credentials['tpaId'] .
			'&expires=' . $credentials['expires'] .
			'&action=' . $credentials['action'] .
			'&flags=' . $credentials['flags'] .
			'&userdata=' . $credentials['userdata'];
		$authenticationDataIsValid = $this->rsaWalletService->verifySignature(
			$authenticationData,
			$credentials['signature'],
			$this->options['rsaKeyUuid']
		);

		if ($authenticationDataIsValid && $credentials['expires'] > time()) {
			$userdata = $this->parseUserdata($credentials['userdata']);
			if (!is_object($account)) {
				$account = $this->createAccount($userdata);
			} elseif (is_object($account)) {
				$account = $this->updateAccount($account, $userdata);
			}

			$authenticationToken->setAuthenticationStatus(TokenInterface::AUTHENTICATION_SUCCESSFUL);
			$authenticationToken->setAccount($account);
		} else {
			$authenticationToken->setAuthenticationStatus(TokenInterface::WRONG_CREDENTIALS);
		}
	}

	/**
	 * @param string $userdata
	 * @return array
	 */
	protected function parseUserdata($userdata) {
		$result = array();
		foreach (explode('|', base64_decode($userdata)) as $line) {
			list($key, $value) = explode('=', $line, 2);
			switch ($key) {
				case 'username':
				case 'name':
				case 'email':
					$result[$key] = $value;
					break;
				case 'tx_t3ouserimage_img_hash':
					break;
			}
		}
		return $result;
	}

	/**
	 * @param array $userdata
	 * @return Account
	 */
	protected function createAccount(array $userdata) {
		if (!isset($userdata['username'])) {
			return;
		}

		$account = new Account();
		$account->setCredentialsSource('typo3.org SSO');
		$account->setAuthenticationProviderName($this->name);
//		$account->setRoles(array(
//			$this->roleRepository->findByIdentifier('AuthenticatedUser')
//		));
		$account->setAccountIdentifier($userdata['username']);

		$person = new Person();
		$this->partyRepository->add($person);
		$this->persistenceManager->whitelistObject($person);
		$account->setParty($person);
		$this->updatePerson($person, $userdata);

		$this->accountRepository->add($account);
		$this->persistenceManager->whitelistObject($account);
		$this->persistenceManager->persistAll(true);
		return $account;
	}

	/**
	 * @param Account $account
	 * @param array $userdata
	 * @return \TYPO3\Flow\Security\Account
	 */
	protected function updateAccount(Account $account, array $userdata) {
		$person = $account->getParty();
		if ($person === null) {
			$person = new Person();
			$this->partyRepository->add($person);
			$this->persistenceManager->whitelistObject($person);
			$account->setParty($person);
		}

		$this->updatePerson($person, $userdata);

		$this->accountRepository->update($account);
		$this->persistenceManager->whitelistObject($account);
		$this->persistenceManager->persistAll(true);
		return $account;
	}

	/**
	 * @param Person $person
	 * @param array $userdata
	 */
	protected function updatePerson(Person $person, array $userdata) {
		if (isset($userdata['name'])) {
			$personName = $person->getName();
			if ($personName === null) {
				$personName = new PersonName();
				$person->setName($personName);
			}
			$this->updateName($personName, $userdata['name']);
		}
		if (isset($userdata['email'])) {
			$primaryElectronicAddress = $person->getPrimaryElectronicAddress();
			if ($primaryElectronicAddress === null) {
				$primaryElectronicAddress = new ElectronicAddress();
				$person->setPrimaryElectronicAddress($primaryElectronicAddress);
			}
			$primaryElectronicAddress->setType(ElectronicAddress::TYPE_EMAIL);
			$primaryElectronicAddress->setIdentifier($userdata['email']);
		}
	}

	/**
	 * @param PersonName $personName
	 * @param string $name
	 */
	protected function updateName(PersonName $personName, $name) {
		$data = array(
			'firstName' => '',
			'middleName' => array(),
			'lastName' => '',
			'otherName' => array(),
		);
		$nameParts = array_filter(
			explode(' ', $name),
			function($nameSegment) use (&$data) {
				if (strpos($nameSegment, '(') !== false) {
					$data['otherName'][] = $nameSegment;
					return false;
				}
				return true;
			}
		);
		$lastPosition = count($nameParts) - 1;
		foreach (array_values($nameParts) as $position => $nameSegment) {
			switch ($position) {
				case 0:
					$data['firstName'] = $nameSegment;
					break;
				case $position === $lastPosition:
					$data['lastName'] = $nameSegment;
					break;
				default:
					$data['middleName'][] = $nameSegment;
					break;
			}
		}
		foreach ($data as $segmentName => $nameSegment) {
			$nameSegment = is_array($nameSegment) ? implode(' ', $nameSegment) : $nameSegment;
			$personName->{'set' . ucfirst($segmentName)}($nameSegment);
		}
	}

}