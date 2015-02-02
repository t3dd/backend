<?php
namespace T3DD\Backend\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Participant {

	/**
	 * @var \TYPO3\Flow\Security\Account
	 * @ORM\OneToOne(mappedBy="accountIdentifier")
	 */
	protected $account = NULL;

	/**
	 * @var \DateTime
	 */
	protected $date;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $name = '';

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $company = '';

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $street = '';

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $zip = '';

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $city = '';

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $country = '';

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $footType = 'default';

	/**
	 * @var string
	 */
	protected $footWishes = '';

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $tshirtSize;

	/**
	 * @var boolean
	 */
	protected $noob = true;

	/**
	 * @var boolean
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $room = false;

	/**
	 * @var integer
	 */
	protected $roomSize = 0;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\T3DD\Backend\Domain\Model\Mate>
	 * @ORM\ManyToMany(inversedBy="roomMates")
	 */
	protected $roomMates;

	public function __construct() {
		$this->date = new \DateTime();
		$this->roomMates = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @return \TYPO3\Flow\Security\Account
	 */
	public function getAccount() {
		return $this->account;
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 */
	public function setAccount($account) {
		$this->account = $account;
	}

	/**
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @param \DateTime $date
	 */
	public function setDate($date) {
		$this->date = $date;
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
	public function getCompany() {
		return $this->company;
	}

	/**
	 * @param string $company
	 */
	public function setCompany($company) {
		$this->company = $company;
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

	/**
	 * @return mixed
	 */
	public function getFootType() {
		return $this->footType;
	}

	/**
	 * @param mixed $footType
	 */
	public function setFootType($footType) {
		$this->footType = $footType;
	}

	/**
	 * @return string
	 */
	public function getFootWishes() {
		return $this->footWishes;
	}

	/**
	 * @param string $footWishes
	 */
	public function setFootWishes($footWishes) {
		$this->footWishes = $footWishes;
	}

	/**
	 * @return string
	 */
	public function getTshirtSize() {
		return $this->tshirtSize;
	}

	/**
	 * @param string $tshirtSize
	 */
	public function setTshirtSize($tshirtSize) {
		$this->tshirtSize = $tshirtSize;
	}

	/**
	 * @return boolean
	 */
	public function isNoob() {
		return $this->noob;
	}

	/**
	 * @param boolean $noob
	 */
	public function setNoob($noob) {
		$this->noob = $noob;
	}

	/**
	 * @return boolean
	 */
	public function isRoom() {
		return $this->room;
	}

	/**
	 * @param boolean $room
	 */
	public function setRoom($room) {
		$this->room = $room;
	}

	/**
	 * @return int
	 */
	public function getRoomSize() {
		return $this->roomSize;
	}

	/**
	 * @param int $roomSize
	 */
	public function setRoomSize($roomSize) {
		$this->roomSize = $roomSize;
	}

	/**
	 * @return \T3DD\Backend\Domain\Model\Mate[]
	 */
	public function getRoomMates() {
		return $this->roomMates;
	}

	/**
	 * @param \T3DD\Backend\Domain\Model\Mate $roomMate
	 */
	public function addRoomMate(\T3DD\Backend\Domain\Model\Mate $roomMate) {
		$this->roomMates->add($roomMate);
	}

	/**
	 * @var \Doctrine\Common\Collections\Collection<\T3DD\Backend\Domain\Model\Mate> $rootMates
	 */
	public function setRoomMates(\Doctrine\Common\Collections\Collection $roomMates) {
		$this->roomMates = $roomMates;
	}


}