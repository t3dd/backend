<?php
namespace T3DD\Backend\Domain\Model\DataTransfer;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use Netlogix\Crud\Domain\Model\DataTransfer\UriPointer;
use Netlogix\Crud\Utility\ArrayUtility;
use TYPO3\Flow\Annotations as Flow;

class Participant extends \Netlogix\Crud\Domain\Model\DataTransfer\AbstractDataTransferObject {

	/**
	 * @var \T3DD\Backend\Domain\Model\Participant
	 */
	protected $payload;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * @param \T3DD\Backend\Domain\Model\Participant $payload
	 */
	public function __construct(\T3DD\Backend\Domain\Model\Participant $payload) {
		parent::__construct($payload);
	}

	/**
	 * @return array<string>
	 */
	public function getPropertyNamesToBeApiExposed() {
		return ['__identity', 'resource', 'rate', 'roomSize', 'companyName', 'name', 'country', 'email', 'foodType', 'foodWishes', 'tshirtType', 'tshirtSize', 'newcomer', 'yearExpertise', 'roomMates'];
	}

	/**
	 * @return \T3DD\Backend\Domain\Model\Participant
	 */
	public function getPayload() {
		return $this->payload;
	}

	/**
	 * @return UriPointer
	 */
	public function getResource() {
		return new UriPointer([
			'packageKey' => 'T3DD.Backend',
			'controllerName' => 'Participant',
			'actionName' => 'index',
			'arguments' => ['participant' => $this->payload],
		]);
	}

	/**
	 * @param string $resource
	 */
	public function setResource($resource) {

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
	 * @return array
	 */
	public function getRoomMates() {
		$rootMates = ArrayUtility::trimExplode(',', $this->getPayload()->getRoomMates());
		$numberOfRoomMatesLeft = $this->getPayload()->getRoomSize() - 1 - count($rootMates);
		for($i = 1; $i <= $numberOfRoomMatesLeft; $i++) {
			$rootMates[] = '';
		}
		return $rootMates;
	}

	/**
	 * @param array $roomMates
	 */
	public function setRoomMates($roomMates) {
		$this->getPayload()->setRoomMates(implode(',', $roomMates));
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

}