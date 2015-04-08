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
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var Participant
	 * @ORM\ManyToOne(inversedBy="roomMates")
	 */
	protected $participant;

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
	 * @return Participant
	 */
	public function getParticipant() {
		return $this->participant;
	}

	/**
	 * @param Participant $participant
	 */
	public function setParticipant(Participant $participant) {
		$this->participant = $participant;
	}

}