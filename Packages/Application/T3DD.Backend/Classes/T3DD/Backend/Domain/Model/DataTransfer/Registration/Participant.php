<?php
namespace T3DD\Backend\Domain\Model\DataTransfer\Registration;

/**
 * Enter descriptions here
 */
class Participant extends \Netlogix\Crud\Domain\Model\DataTransfer\AbstractDataTransferObject {

	/**
	 * @var \T3DD\Backend\Domain\Model\Registration\Participant
	 */
	protected $payload;

	/**
	 * @param \T3DD\Backend\Domain\Model\Registration\Participant $payload
	 */
	public function __construct(\T3DD\Backend\Domain\Model\Registration\Participant $payload) {
		parent::__construct($payload);
	}

	/**
	 * @return array<string>
	 */
	public function getPropertyNamesToBeApiExposed() {
		return array('rate', 'roomSize');
	}

	/**
	 * @return \T3DD\Backend\Domain\Model\Registration\Participant
	 */
	public function getPayload() {
		return $this->payload;
	}

}