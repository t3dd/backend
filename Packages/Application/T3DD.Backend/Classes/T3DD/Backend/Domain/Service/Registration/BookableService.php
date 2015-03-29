<?php
namespace T3DD\Backend\Domain\Service\Registration;

use TYPO3\Flow\Annotations as Flow;
use T3DD\Backend\Domain\Model\Registration;

/**
 * @Flow\Scope("singleton")
 */
class BookableService {

	const DEFAULT_FRACTION_BASE = 1;

	const TYPE_ROOM = 'Room';
	const TYPE_TICKET = 'Ticket';

	/**
	 * @var array
	 * @Flow\InjectConfiguration("Registration.Bookable")
	 */
	protected $configuration;

	/**
	 * @var \T3DD\Backend\Domain\Repository\Registration\RoomRepository
	 * @Flow\Inject
	 */
	protected $roomRepository;

	/**
	 * @var \T3DD\Backend\Domain\Repository\Registration\TicketRepository
	 * @Flow\Inject
	 */
	protected $ticketRepository;

	/**
	 * @param array $roomRequests
	 * @return Registration\Room[]
	 */
	public function requestRooms(array $roomRequests) {
		return $this->requestBookable(static::TYPE_ROOM, $roomRequests);
	}

	/**
	 * @param array $ticketRequests
	 * @return Registration\Ticket[]
	 */
	public function requestTickets(array $ticketRequests) {
		return $this->requestBookable(static::TYPE_TICKET, $ticketRequests);
	}

	/**
	 * @param string $type
	 * @param array $bookableRequests
	 * @return Registration\AbstractBookable[]
	 */
	protected function requestBookable($type, $bookableRequests) {
		/** @var \T3DD\Backend\Domain\Repository\Registration\AbstractBookableRepository $repository */
		$repository = $this->{strtolower($type) . 'Repository'};
		$fractionBase = (int) isset($this->configuration[$type]['fractionBase']) ? $this->configuration[$type]['fractionBase'] : static::DEFAULT_FRACTION_BASE;
		$availableQuota = (int) isset($this->configuration[$type]['availableQuota']) ? $this->configuration[$type]['availableQuota'] * $fractionBase : 0;
		$spentQuota = $repository->getSpentQuota();
		$requestedBookables = [];

		foreach ($bookableRequests as $bookableRequest) {
			$requestFraction = round($fractionBase / $bookableRequest['divisor']);
			$quotaApplies = (bool) isset($bookableRequest['quotaApplies']) ? $bookableRequest['quotaApplies'] : TRUE;

			$className = 'T3DD\\Backend\\Domain\\Model\\Registration\\' . $type;

			$requestedBookable = new $className();
			$requestedBookable->setParticipant($bookableRequest['participant']);
			$requestedBookable->setFraction($requestFraction);
			$requestedBookable->setQuotaApplies($quotaApplies);

			if (!$quotaApplies) {
				$requestedBookable->setBookingState(Registration\AbstractBookable::BOOKING_STATE_PENDING);
			} else {
				$spentQuota += $requestFraction;

				if ($spentQuota > $availableQuota) {
					$requestedBookable->setBookingState(Registration\AbstractBookable::BOOKING_STATE_WAITING);
				} else {
					$requestedBookable->setBookingState(Registration\AbstractBookable::BOOKING_STATE_PENDING);
				}
			}

			$requestedBookables[] = $requestedBookable;
			$repository->add($requestedBookable);
		}

		return $requestedBookables;
	}

}