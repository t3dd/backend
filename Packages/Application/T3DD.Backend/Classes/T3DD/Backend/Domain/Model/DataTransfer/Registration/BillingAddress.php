<?php
namespace T3DD\Backend\Domain\Model\DataTransfer\Registration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class BillingAddress extends \Netlogix\Crud\Domain\Model\DataTransfer\AbstractDataTransferObject {

	/**
	 * @var \T3DD\Backend\Domain\Model\Registration\BillingAddress
	 */
	protected $payload;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * @param \T3DD\Backend\Domain\Model\Registration\BillingAddress $payload
	 */
	public function __construct(\T3DD\Backend\Domain\Model\Registration\BillingAddress $payload) {
		parent::__construct($payload);
	}

	/**
	 * @return array<string>
	 */
	public function getPropertyNamesToBeApiExposed() {
		return array('__identity', 'companyName', 'name', 'email', 'street', 'zip', 'city', 'country');
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

	/**
	 * @param string $companyName
	 */
	public function setCompanyName($companyName) {
		$this->payload->setCompanyName($companyName);
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->payload->setName($name);
	}

	/**
	 * @param string $email
	 */
	public function setEmail($email) {
		$this->payload->setEmail($email);
	}

	/**
	 * @param string $street
	 */
	public function setStreet($street) {
		$this->payload->setStreet($street);
	}

	/**
	 * @param string $zip
	 */
	public function setZip($zip) {
		$this->payload->setZip($zip);
	}

	/**
	 * @param string $city
	 */
	public function setCity($city) {
		$this->payload->setCity($city);
	}

	/**
	 * @param string $country
	 */
	public function setCountry($country) {
		$this->payload->setCountry($country);
	}

}