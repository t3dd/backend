<?php
namespace T3DD\Backend\Domain\Model\DataTransfer\Registration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use Netlogix\Crud\Domain\Model\DataTransfer\UriPointer;
use TYPO3\Flow\Annotations as Flow;

class Participant extends \Netlogix\Crud\Domain\Model\DataTransfer\AbstractDataTransferObject {

	/**
	 * @var \Netlogix\Crud\Domain\Service\DataTransferObjectFactory
	 * @Flow\Inject
	 */
	protected $dataTransferObjectFactory;

	/**
	 * @var \T3DD\Backend\Domain\Model\Registration\Participant
	 */
	protected $payload;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

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
		return array('__identity', 'resource', 'isRegistrant', 'rate', 'roomSize', 'companyName', 'name', 'country', 'email', 'foodType', 'foodWishes', 'tshirtType', 'tshirtSize', 'newcomer', 'yearExpertise', 'ticketBookingState', 'roomBookingState', 'roomMates');
	}

	/**
	 * @return \T3DD\Backend\Domain\Model\Registration\Participant
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
			'controllerName' => 'Participant',
			'actionName' => 'index',
			'arguments' => array('participant' => $this->payload),
		));
	}

	/**
	 * @param string $resource
	 */
	public function setResource($resource) {

	}

	/**
	 * @param boolean $isRegistrant
	 */
	public function setIsRegistrant($isRegistrant) {

	}

	/**
	 * @param string $name
	 */
	public function setName($name) {

	}

	/**
	 * @param string $country
	 */
	public function setCountry($country) {

	}

	/**
	 * @param string $email
	 */
	public function setEmail($email) {

	}

	/**
	 * @param string $companyName
	 */
	public function setCompanyName($companyName) {
		$this->getPayload()->setCompanyName($companyName);
	}

	/**
	 * @param string $rate
	 */
	public function setRate($rate) {

	}

	/**
	 * @param int $roomSize
	 */
	public function setRoomSize($roomSize) {

	}

	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getRoomMates() {
		return $this->dataTransferObjectFactory->getDataTransferObjects($this->getPayload()->getRoomMates());
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $roomMates
	 */
	public function setRoomMates($roomMates) {
		$this->getPayload()->setRoomMates($roomMates);
	}

	/**
	 * @param string $roomGroup
	 */
	public function setRoomGroup($roomGroup) {
		$this->getPayload()->setRoomGroup($roomGroup);
	}

	/**
	 * @param string $foodType
	 */
	public function setFoodType($foodType) {
		$this->getPayload()->setFoodType($foodType);
	}

	/**
	 * @param string $foodWishes
	 */
	public function setFoodWishes($foodWishes) {
		$this->getPayload()->setFoodWishes($foodWishes);
	}

	/**
	 * @param string $tshirtSize
	 */
	public function setTshirtSize($tshirtSize) {
		$this->getPayload()->setTshirtSize($tshirtSize);
	}

	/**
	 * @param string $tshirtType
	 */
	public function setTshirtType($tshirtType) {
		$this->getPayload()->setTshirtType($tshirtType);
	}

	/**
	 * @param boolean $newcomer
	 */
	public function setNewcomer($newcomer) {
		$this->getPayload()->setNewcomer($newcomer);
	}

	/**
	 * @param int $yearExpertise
	 */
	public function setYearExpertise($yearExpertise) {
		$this->getPayload()->setYearExpertise($yearExpertise);
	}

	/**
	 * @return string
	 */
	public function getTicketBookingState() {
		if ($this->payload->getTicket()) {
			return $this->payload->getTicket()->getBookingState();
		}
	}

	/**
	 * @param string $ticketBookingState
	 */
	public function setTicketBookingState($ticketBookingState) {

	}

	/**
	 * @return string
	 */
	public function getRoomBookingState() {
		if ($this->payload->getRoom()) {
			return $this->payload->getRoom()->getBookingState();
		}
	}

	/**
	 * @param string $roomBookingState
	 */
	public function setRoomBookingState($roomBookingState) {

	}

}