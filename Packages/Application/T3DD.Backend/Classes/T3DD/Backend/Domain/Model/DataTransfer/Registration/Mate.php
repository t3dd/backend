<?php
namespace T3DD\Backend\Domain\Model\DataTransfer\Registration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class Mate extends \Netlogix\Crud\Domain\Model\DataTransfer\AbstractDataTransferObject {

	/**
	 * @var \T3DD\Backend\Domain\Model\Registration\Mate
	 */
	protected $payload;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * @param \T3DD\Backend\Domain\Model\Registration\Mate $payload
	 */
	public function __construct(\T3DD\Backend\Domain\Model\Registration\Mate $payload) {
		parent::__construct($payload);
	}

	/**
	 * @return array<string>
	 */
	public function getPropertyNamesToBeApiExposed() {
		return array('__identity', 'name');
	}

	/**
	 * @return \T3DD\Backend\Domain\Model\Registration\Participant
	 */
	public function getPayload() {
		return $this->payload;
	}

	/**
	 * @return string
	 */
	public function getIdentifier() {
		return $this->persistenceManager->getIdentifierByObject($this->payload);
	}

}