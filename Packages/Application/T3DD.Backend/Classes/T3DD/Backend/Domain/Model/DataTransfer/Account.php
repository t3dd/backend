<?php
namespace T3DD\Backend\Domain\Model\DataTransfer;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

class Account extends \Netlogix\Crud\Domain\Model\DataTransfer\AbstractDataTransferObject {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Party\Domain\Service\PartyService
	 */
	protected $partyService;

	/**
	 * @var \TYPO3\Flow\Security\Account
	 */
	protected $payload;

	/**
	 * @param \TYPO3\Flow\Security\Account
	 */
	public function __construct($payload) {
		parent::__construct($payload);
	}

	/**
	 * @return array<string>
	 */
	public function getPropertyNamesToBeApiExposed() {
		return array('displayName', 'profileImage');
	}

	/**
	 * @return \TYPO3\Flow\Security\Account
	 */
	public function getPayload() {
		return $this->payload;
	}

	/**
	 * @return string
	 */
	public function getDisplayName() {
		return (string)$this->partyService->getAssignedPartyOfAccount($this->payload)->getName();
	}

	/**
	 * @return string
	 */
	public function getProfileImage() {
		return sprintf('//typo3.org/services/userimage.php?username=%s&size=big', $this->payload->getAccountIdentifier());
	}

}