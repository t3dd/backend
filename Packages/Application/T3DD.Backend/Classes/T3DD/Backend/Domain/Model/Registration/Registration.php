<?php
namespace T3DD\Backend\Domain\Model\Registration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * @Flow\Entity
 */
class Registration {

	/**
	 * @var \DateTime
	 */
	protected $date;

	/**
	 * @var \TYPO3\Flow\Security\Account
	 * @ORM\ManyToOne
	 */
	protected $account = NULL;

	/**
	 * @var integer
	 */
	protected $participantCount;

	/**
	 * @var BillingAddress
	 * @ORM\OneToMany(mappedBy="registration")
	 */
	protected $billingAddress;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection<Participant>
	 * @ORM\OneToMany(mappedBy="registration", cascade={"all"})
	 */
	protected $participants;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $notes = '';

	/**
	 * @var bool
	 */
	protected $completed = false;

	public function __construct() {
		$this->date = new \DateTime();
		$this->participants = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @return int
	 */
	public function getParticipantCount() {
		return $this->participantCount;
	}

	/**
	 * @param int $participantCount
	 */
	public function setParticipantCount($participantCount) {
		$this->participantCount = $participantCount;
	}

	/**
	 * @return string
	 */
	public function getBillingAddress() {
		return $this->billingAddress;
	}

	/**
	 * @param string $billingAddress
	 */
	public function setBillingAddress($billingAddress) {
		$this->billingAddress = $billingAddress;
	}

	/**
	 * @return Collection
	 */
	public function getParticipants() {
		return $this->participants;
	}

	/**
	 * @param Collection $participants
	 */
	public function setParticipants($participants) {
		$this->participants = $participants;
	}

	/**
	 * @param Participant $participant
	 */
	public function addParticipant(\T3DD\Backend\Domain\Model\Registration\Participant $participant) {
		$this->participants->add($participant);
	}

	/**
	 * @param Participant $participant
	 */
	public function removeParticipant(\T3DD\Backend\Domain\Model\Registration\Participant $participant) {
		$this->participants->remove($participant);
	}

	/**
	 * @return mixed
	 */
	public function getNotes() {
		return $this->notes;
	}

	/**
	 * @param mixed $notes
	 */
	public function setNotes($notes) {
		$this->notes = $notes;
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

}