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
class Participant {

	/**
	 * @var Registration
	 * @ORM\ManyToOne
	 */
	protected $registration = NULL;

	/**
	 * @var \TYPO3\Flow\Security\Account
	 * @ORM\ManyToOne
	 */
	protected $account = NULL;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $name = '';

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $email = '';

	/**
	 * @var string
	 */
	protected $company = '';

	/**
	 * @var string
	 */
	protected $country = '';

	/**
	 * @var string
	 * @ORM\Column(type="string", columnDefinition="ENUM('noassoc', 'assoc', 'css', 'helper', 'sponsornonassoc', 'sponsornassoc', 'voucher')")
	 */
	protected $rate;

	/**
	 * @var integer
	 * @ORM\Column(type="string", columnDefinition="ENUM('0', '2', '3', '4')")
	 */
	protected $roomSize = 0;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\T3DD\Backend\Domain\Model\Registration\Mate>
	 * @ORM\ManyToMany(inversedBy="roomMates")
	 */
	protected $roomMates;

	/**
	 * @var string
	 */
	protected $roomGroup;

	/**
	 * @var string
	 * @ORM\Column(type="string", columnDefinition="ENUM('default', 'vegetarian', 'vegan')")
	 */
	protected $foodType = 'default';

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 * @Flow\Validate(type="Raw")
	 */
	protected $foodWishes = '';

	/**
	 * @var string
	 * @ORM\Column(type="string", columnDefinition="ENUM('xs', 's', 'm', 'l', 'xl', 'xxl', '3xl', '4xl')")
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $tshirtSize;

	/**
	 * @var string
	 * @ORM\Column(type="string", columnDefinition="ENUM('default', 'girlie')")
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $tshirtType = 'default';

	/**
	 * @var boolean
	 */
	protected $newcomer = true;

	/**
	 * @var integer
	 */
	protected $yearExpertise = true;

	/**
	 * @var Ticket
	 * @ORM\OneToMany(mappedBy="participant")
	 */
	protected $ticket;

	/**
	 * @var Room
	 * @ORM\OneToMany(mappedBy="participant")
	 */
	protected $room;

	public function __construct() {
		$this->date = new \DateTime();
		$this->roomMates = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @return Registration
	 */
	public function getRegistration() {
		return $this->registration;
	}

	/**
	 * @param Registration $registration
	 */
	public function setRegistration($registration) {
		$this->registration = $registration;
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
	 * @return mixed
	 */
	public function getCompany() {
		return $this->company;
	}

	/**
	 * @param mixed $company
	 */
	public function setCompany($company) {
		$this->company = $company;
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
	public function getRate() {
		return $this->rate;
	}

	/**
	 * @param mixed $rate
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
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getRoomMates() {
		return $this->roomMates;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $roomMates
	 */
	public function setRoomMates($roomMates) {
		$this->roomMates = $roomMates;
	}

	/**
	 * @return mixed
	 */
	public function getRoomGroup() {
		return $this->roomGroup;
	}

	/**
	 * @param mixed $roomGroup
	 */
	public function setRoomGroup($roomGroup) {
		$this->roomGroup = $roomGroup;
	}

	/**
	 * @return mixed
	 */
	public function getFoodType() {
		return $this->foodType;
	}

	/**
	 * @param mixed $foodType
	 */
	public function setFoodType($foodType) {
		$this->foodType = $foodType;
	}

	/**
	 * @return mixed
	 */
	public function getFoodWishes() {
		return $this->foodWishes;
	}

	/**
	 * @param mixed $foodWishes
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
	 * @return mixed
	 */
	public function getNewcomer() {
		return $this->newcomer;
	}

	/**
	 * @param mixed $newcomer
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
	 * @return mixed
	 */
	public function getTicket() {
		return $this->ticket;
	}

	/**
	 * @param mixed $ticket
	 */
	public function setTicket($ticket) {
		$this->ticket = $ticket;
	}

	/**
	 * @return mixed
	 */
	public function getRoom() {
		return $this->room;
	}

	/**
	 * @param mixed $room
	 */
	public function setRoom($room) {
		$this->room = $room;
	}

}