<?php
namespace T3DD\Backend\Domain\Model\DataTransfer\Registration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use Doctrine\Common\Collections\Collection;
use Netlogix\Crud\Domain\Model\DataTransfer\UriPointer;
use TYPO3\Flow\Annotations as Flow;

class Registration extends \Netlogix\Crud\Domain\Model\DataTransfer\AbstractDataTransferObject {

	/**
	 * @var \Netlogix\Crud\Domain\Service\DataTransferObjectFactory
	 * @Flow\Inject
	 */
	protected $dataTransferObjectFactory;

	/**
	 * @var \T3DD\Backend\Domain\Model\Registration\Registration
	 */
	protected $payload;

	/**
	 * @param \T3DD\Backend\Domain\Model\Registration\Registration $payload
	 */
	public function __construct(\T3DD\Backend\Domain\Model\Registration\Registration $payload) {
		parent::__construct($payload);
	}

	/**
	 * @return array<string>
	 */
	public function getPropertyNamesToBeApiExposed() {
		return array('resource', 'participantCount', 'participants', 'billingAddress', 'secondsToExpiration', 'notes');
	}

	/**
	 * @return \T3DD\Backend\Domain\Model\Registration\Registration
	 */
	public function getPayload() {
		return $this->payload;
	}

	/**
	 * @return UriPointer
	 */
	public function getResource() {
		return new UriPointer(array(
			'packageKey' => 'T3DD.Backend',
			'controllerName' => 'Registration',
			'actionName' => 'index',
			'arguments' => array('registration' => $this->payload),
		));
	}

	/**
	 * @param string $resource
	 */
	public function setResource($resource) {

	}

	/**
	 * @param integer $participantCount
	 */
	public function setParticipantCount($participantCount) {
		$this->payload->setParticipantCount($participantCount);
	}

	/**
	 * @param Collection<\T3DD\Backend\Domain\Model\Registration\Participant> $participants
	 */
	public function setParticipants(Collection $participants) {
		$this->payload->setParticipants($participants);
	}

	/**
	 * @return array
	 */
	public function getParticipants() {
		return $this->dataTransferObjectFactory->getDataTransferObjects($this->payload->getParticipants());
	}

	/**
	 * @param \T3DD\Backend\Domain\Model\Registration\BillingAddress $billingAddress
	 */
	public function setBillingAddress(\T3DD\Backend\Domain\Model\Registration\BillingAddress $billingAddress) {
		$this->payload->setBillingAddress($billingAddress);
	}

	/**
	 * @return \T3DD\Backend\Domain\Model\DataTransfer\Registration\BillingAddress
	 */
	public function getBillingAddress() {
		$billingAddress = $this->payload->getBillingAddress();
		if ($billingAddress !== NULL) {
			return $this->dataTransferObjectFactory->getDataTransferObject($billingAddress);
		}
	}

	/**
	 * @return integer
	 */
	public function getSecondsToExpiration() {
		return $this->payload->getSecondsToExpiration();
	}

	/**
	 * @param integer $secondsToExpiration
	 */
	public function setSecondsToExpiration($secondsToExpiration) {

	}

	/**
	 * @param string $notes
	 */
	public function setNotes($notes) {
		$this->payload->setNotes($notes);
	}

}