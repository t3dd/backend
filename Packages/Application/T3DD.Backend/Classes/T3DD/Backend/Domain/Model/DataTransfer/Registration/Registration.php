<?php
namespace T3DD\Backend\Domain\Model\DataTransfer\Registration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

class Registration extends \Netlogix\Crud\Domain\Model\DataTransfer\AbstractDataTransferObject {

	/**
	 * @var \T3DD\Backend\Domain\Model\Registration\Registration
	 */
	protected $payload;

	/**
	 * @param \T3DD\Backend\Domain\Model\Registration\Registration $payload
	 */
	public function __construct($payload) {
		parent::__construct($payload);
	}

	/**
	 * @return array<string>
	 */
	public function getPropertyNamesToBeApiExposed() {
		return array('participantCount');
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

}