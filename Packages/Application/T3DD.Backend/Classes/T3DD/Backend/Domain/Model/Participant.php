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
	 * @ORM\ManyToOne
	 */
	protected $account = NULL;

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $email = '';

	/**
	 * @var string
	 */
	protected $companyName = '';

	/**
	 * @var string
	 */
	protected $country = '';

	/**
	 * @var string
	 * @ORM\Column(type="string", columnDefinition="ENUM('nonassoc', 'assoc', 'student', 'speaker', 'externalspeaker', 'core', 'helper', 'sponsornonassoc', 'sponsorassoc', 'voucher', 'rookie')")
	 */
	protected $rate;

	/**
	 * @var integer
	 * @ORM\Column(type="string", columnDefinition="ENUM('0', '1', '2', '3', '4')")
	 */
	protected $roomSize = 0;

	/**
	 * @var string
	 */
	protected $roomMates;

	/**
	 * @var string
	 * @ORM\Column(type="string", columnDefinition="ENUM('default', 'vegetarian', 'vegan', 'other')")
	 */
	protected $foodType = 'default';

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $foodWishes = '';

	/**
	 * @var string
	 * @ORM\Column(type="string", columnDefinition="ENUM('xs', 's', 'm', 'l', 'xl', 'xxl', '3xl', '4xl')")
	 */
	protected $tshirtSize = 'l';

	/**
	 * @var string
	 * @ORM\Column(type="string", columnDefinition="ENUM('default', 'girlie')")
	 */
	protected $tshirtType = 'default';

	/**
	 * @var boolean
	 */
	protected $newcomer = true;

	/**
	 * @var integer
	 */
	protected $yearExpertise = 0;

	/**
	 * @var bool
	 */
	protected $completed = false;

	/**
	 * @var \DateTime
	 */
	protected $lastEmailSent;

	public function __construct() {
		$this->date = new \DateTime();
		$this->lastEmailSent = new \DateTime();
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
	public function setAccount(\TYPO3\Flow\Security\Account $account) {
		$this->account = $account;
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\Person $person
	 */
	public function setPerson(\TYPO3\Party\Domain\Model\Person $person) {
		$this->name = (string) $person->getName();
		$this->email = $person->getPrimaryElectronicAddress()->getIdentifier();
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
	 * @return int
	 */
	public function getRate() {
		return $this->rate;
	}

	/**
	 * @param int $rate
	 */
	public function setRate($rate) {
		$this->rate = $rate;
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
	 * @return string
	 */
	public function getRoomMates() {
		return $this->roomMates;
	}

	/**
	 * @param string
	 */
	public function setRoomMates($roomMates) {
		$this->roomMates = $roomMates;
	}

	/**
	 * @return string
	 */
	public function getFoodType() {
		return $this->foodType;
	}

	/**
	 * @param string $foodType
	 */
	public function setFoodType($foodType) {
		$this->foodType = $foodType;
	}

	/**
	 * @return string
	 */
	public function getFoodWishes() {
		return $this->foodWishes;
	}

	/**
	 * @param string $foodWishes
	 */
	public function setFoodWishes($foodWishes) {
		$this->foodWishes = $foodWishes;
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
	 * @return string
	 */
	public function getTshirtType() {
		return $this->tshirtType;
	}

	/**
	 * @param string $tshirtType
	 */
	public function setTshirtType($tshirtType) {
		$this->tshirtType = $tshirtType;
	}

	/**
	 * @return boolean
	 */
	public function getNewcomer() {
		return $this->newcomer;
	}

	/**
	 * @param boolean $newcomer
	 */
	public function setNewcomer($newcomer) {
		$this->newcomer = $newcomer;
	}

	/**
	 * @return int
	 */
	public function getYearExpertise() {
		return $this->yearExpertise;
	}

	/**
	 * @param int $yearExpertise
	 */
	public function setYearExpertise($yearExpertise) {
		$this->yearExpertise = $yearExpertise;
	}

	/**
	 * @return boolean
	 */
	public function isCompleted() {
		return $this->completed;
	}

	/**
	 * @param boolean $completed
	 */
	public function setCompleted($completed) {
		$this->completed = $completed;
	}

	/**
	 * @param \DateTime $lastEmailSent
	 */
	public function setLastEmailSent($lastEmailSent) {
		$this->lastEmailSent = $lastEmailSent;
	}

}