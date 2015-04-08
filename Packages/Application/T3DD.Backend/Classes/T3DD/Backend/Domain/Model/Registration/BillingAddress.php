<?php
namespace T3DD\Backend\Domain\Model\Registration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class BillingAddress {

	/**
	 * @var string
	 */
	protected $companyName = '';

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $name;

	/**
	 * @var string
	 * @Flow\Validate(type="EmailAddress")
	 */
	protected $email;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $street;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $zip;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $city;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $country;

	/**
	 * @return string
	 */
	public function getCompanyName() {
		return $this->companyName;
	}

	/**
	 * @param string $companyName
	 */
	public function setCompanyName($companyName) {
		$this->companyName = $companyName;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getStreet() {
		return $this->street;
	}

	/**
	 * @param string $street
	 */
	public function setStreet($street) {
		$this->street = $street;
	}

	/**
	 * @return string
	 */
	public function getZip() {
		return $this->zip;
	}

	/**
	 * @param string $zip
	 */
	public function setZip($zip) {
		$this->zip = $zip;
	}

	/**
	 * @return string
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * @param string $city
	 */
	public function setCity($city) {
		$this->city = $city;
	}

	/**
	 * @return string
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * @param string $country
	 */
	public function setCountry($country) {
		$this->country = $country;
	}

}