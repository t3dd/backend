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
class Mate {

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
	 * @var \Doctrine\Common\Collections\Collection<\T3DD\Backend\Domain\Model\Registration\Participant>
	 * @ORM\ManyToMany(mappedBy="roomMates")
	 */
	protected $participants;

	public function __construct() {
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
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getParticipants() {
		return $this->participants;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $participants
	 */
	public function setParticipants($participants) {
		$this->participants = $participants;
	}

}