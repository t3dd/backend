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
	 * @ORM\ManyToOne(inversedBy="participants", cascade={"all"})
	 */
	protected $registration = NULL;

	/**
	 * @var int
	 */
	protected $positionInRegistration;

	/**
	 * @var \TYPO3\Flow\Security\Account
	 * @ORM\ManyToOne
	 */
	protected $account = NULL;

	/**
	 * @var bool
	 */
	protected $isRegistrant = false;

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
	 * @ORM\Column(type="string", columnDefinition="ENUM('noassoc', 'assoc', 'student', 'speaker', 'core', 'helper', 'sponsornonassoc', 'sponsornassoc', 'voucher')")
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
	protected $roomGroup = '';

	/**
	 * @var string
	 * @ORM\Column(type="string", columnDefinition="ENUM('default', 'vegetarian', 'vegan')")
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
	 * @var Ticket
	 * @ORM\OneToOne(cascade={"all"})
	 */
	protected $ticket;

	/**
	 * @var Room
	 * @ORM\OneToOne(cascade={"all"})
	 */
	protected $room;

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
	 * @return int
	 */
	public function getPositionInRegistration() {
		return $this->positionInRegistration;
	}

	/**
	 * @param int $positionInRegistration
	 */
	public function setPositionInRegistration($positionInRegistration) {
		$this->positionInRegistration = (int) $positionInRegistration;
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
	 * @return boolean
	 */
	public function isRegistrant() {
		return $this->isRegistrant;
	}

	/**
	 * @return boolean
	 */
	public function getIsRegistrant() {
		return $this->isRegistrant;
	}

	/**
	 * @param boolean $isRegistrant
	 */
	public function setIsRegistrant($isRegistrant) {
		$this->isRegistrant = (bool) $isRegistrant;
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
	 * @return string
	 */
	public function getRoomGroup() {
		return $this->roomGroup;
	}

	/**
	 * @param string $roomGroup
	 */
	public function setRoomGroup($roomGroup) {
		$this->roomGroup = $roomGroup;
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
	 * @return Ticket
	 */
	public function getTicket() {
		return $this->ticket;
	}

	/**
	 * @param Ticket $ticket
	 */
	public function setTicket(Ticket $ticket) {
		$this->ticket = $ticket;
	}

	/**
	 * @return Room
	 */
	public function getRoom() {
		return $this->room;
	}

	/**
	 * @param Room $room
	 */
	public function setRoom(Room $room) {
		$this->room = $room;
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

	/**
	 * @return array|null
	 */
	public function getRoomRequest() {
		if (!$this->roomSize) {
			return NULL;
		}
		$roomRequest = [
			'participant' => $this,
			'divisor' => $this->roomSize,
			'quotaApplies' => TRUE
		];
		switch ($this->rate) {
			case 'core':
			case 'helper':
				$roomRequest['quotaApplies'] = FALSE;
				break;
		}
		return $roomRequest;
	}

	/**
	 * @return array
	 */
	public function getTicketRequest() {
		$ticketRequest = [
			'participant' => $this,
			'divisor' => 1,
			'quotaApplies' => TRUE
		];
		switch ($this->rate) {
			case 'core':
			case 'helper':
				$ticketRequest['quotaApplies'] = FALSE;
				break;
		}
		return $ticketRequest;
	}

}