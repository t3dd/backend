<?php
namespace T3DD\Backend\Domain\Model\DataTransfer\Registration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use Doctrine\Common\Collections\Collection;
use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

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
		return array('participantCount', 'participants');
	}

	/**
	 * @return \T3DD\Backend\Domain\Model\Registration\Registration
	 */
	public function getPayload() {
		return $this->payload;
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

}