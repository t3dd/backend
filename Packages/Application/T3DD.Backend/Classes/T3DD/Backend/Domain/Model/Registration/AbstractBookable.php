<?php
namespace T3DD\Backend\Domain\Model\Registration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractBookable {

	const BOOKING_STATE_PENDING = 'pending';
	const BOOKING_STATE_WAITING = 'waiting';
	const BOOKING_STATE_BOOKED = 'booked';

	/**
	 * @var \DateTime
	 */
	protected $date;

	/**
	 * @var Participant
	 * @ORM\ManyToOne
	 */
	protected $participant;

	/**
	 * @var int
	 */
	protected $fraction;

	/**
	 * @var string
	 * @ORM\Column(type="string", columnDefinition="ENUM('pending', 'waiting', 'booked')")
	 */
	protected $bookingState = 'pending';

	/**
	 * @var boolean
	 */
	protected $quotaApplies = TRUE;

	public function __construct() {
		$this->date = new \DateTime();
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

	/**
	 * @return int
	 */
	public function getFraction() {
		return $this->fraction;
	}

	/**
	 * @param int $fraction
	 */
	public function setFraction($fraction) {
		$this->fraction = $fraction;
	}

	/**
	 * @return string
	 */
	public function getBookingState() {
		return $this->bookingState;
	}

	/**
	 * @param string $bookingState
	 */
	public function setBookingState($bookingState) {
		$this->bookingState = $bookingState;
	}

	/**
	 * @return boolean
	 */
	public function isQuotaApplies() {
		return $this->quotaApplies;
	}

	/**
	 * @param boolean $quotaApplies
	 */
	public function setQuotaApplies($quotaApplies) {
		$this->quotaApplies = $quotaApplies;
	}

}