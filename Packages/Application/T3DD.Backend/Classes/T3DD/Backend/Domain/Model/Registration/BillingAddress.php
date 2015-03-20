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
	 * @var Registration
	 * @ORM\ManyToOne
	 */
	protected $registration;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $companyName;

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
	 * @return mixed
	 */
	public function getCompanyName() {
		return $this->companyName;
	}

	/**
	 * @param mixed $companyName
	 */
	public function setCompanyName($companyName) {
		$this->companyName = $companyName;
	}

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getStreet() {
		return $this->street;
	}

	/**
	 * @param mixed $street
	 */
	public function setStreet($street) {
		$this->street = $street;
	}

	/**
	 * @return mixed
	 */
	public function getZip() {
		return $this->zip;
	}

	/**
	 * @param mixed $zip
	 */
	public function setZip($zip) {
		$this->zip = $zip;
	}

	/**
	 * @return mixed
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * @param mixed $city
	 */
	public function setCity($city) {
		$this->city = $city;
	}

	/**
	 * @return mixed
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * @param mixed $country
	 */
	public function setCountry($country) {
		$this->country = $country;
	}

}