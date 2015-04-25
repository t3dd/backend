<?php
namespace T3DD\Backend\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use T3DD\Backend\Domain\Model\Registration\AbstractBookable;
use T3DD\Backend\Domain\Model\Registration\Participant;
use T3DD\Backend\Domain\Model\Registration\Registration;
use T3DD\Backend\Domain\Model\Registration\Room;
use T3DD\Backend\Domain\Model\Registration\Ticket;
use T3DD\Backend\Domain\Repository\Registration\RegistrationRepository;
use T3DD\Backend\Domain\Repository\Registration\ParticipantRepository;
use T3DD\Backend\Domain\Service\MailService;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;

class RegistrationCommandController extends CommandController {

	/**
	 * @var RegistrationRepository
	 * @Flow\Inject
	 */
	protected $registrationRepository;

	/**
	 * @var ParticipantRepository
	 * @Flow\Inject
	 */
	protected $participantRepository;

	/**
	 * @var MailService
	 * @Flow\Inject
	 */
	protected $mailService;

	/**
	 * @var \T3DD\Backend\Domain\Service\Registration\BookableService
	 * @Flow\Inject
	 */
	protected $bookableService;

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
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	public function initializeObject() {
		putenv('FLOW_REWRITEURLS=1');
	}

	/**
	 * Remove uncompleted registrations and update waiting list
	 */
	public function removeUncompletedRegistrationsCommand() {
		$registrations = $this->registrationRepository->findUncompletedRegistrationsToRemove();
		/** @var Registration $registration */
		foreach ($registrations as $registration) {
			$this->registrationRepository->remove($registration);
		}
		$this->persistenceManager->persistAll();
		$this->updateWaitingListCommand();
	}

	/**
	 * Move tickets and rooms from the waiting list
	 * to pending if there are free quota
	 */
	public function updateWaitingListCommand() {
		$registrationsToNotify = [];

		$numberOfTickets = $this->bookableService->getTicketsStatus();
		if ($numberOfTickets > 0) {
			$waitingTickets = $this->ticketRepository->findWaitingByCount($numberOfTickets);
			/** @var Ticket $ticket */
			foreach ($waitingTickets as $ticket) {
				$priorBookingState = $ticket->getBookingState();
				$ticket->setBookingState(AbstractBookable::BOOKING_STATE_PENDING);
				$this->ticketRepository->update($ticket);
				/** @var Participant $participant */
				$participant = $this->participantRepository->findOneByTicket($ticket);
				/** @var Registration $notification */
				$notification = $participant->getRegistration();
				$registrationIdentifier = $this->persistenceManager->getIdentifierByObject($notification);
				if (!array_key_exists($registrationIdentifier, $registrationsToNotify)) {
					$registrationsToNotify[$registrationIdentifier] = [
						'registration' => $notification,
						'ticketBookingStateChanged' => [],
						'roomBookingStateChanged' => [],
					];
				}
				$participantIdentifier = $this->persistenceManager->getIdentifierByObject($participant);
				$registrationsToNotify[$registrationIdentifier]['ticketBookingStateChanged'][$participantIdentifier] = [
					'participant' => $participant,
					'priorBookingState' => $priorBookingState,
					'newBookingState' => $ticket->getBookingState()
				];
			}
		}

		$numberOfRooms = $this->bookableService->getTicketsStatus();
		if ($numberOfRooms > 0) {
			$waitingRooms = $this->roomRepository->findWaitingByCount($numberOfRooms);
			/** @var Room $room */
			foreach ($waitingRooms as $room) {
				$priorBookingState = $room->getBookingState();
				$room->setBookingState(AbstractBookable::BOOKING_STATE_PENDING);
				$this->roomRepository->update($room);
				/** @var Participant $participant */
				$participant = $this->participantRepository->findOneByRoom($room);
				/** @var Registration $registration */
				$notification = $participant->getRegistration();
				$registrationIdentifier = $this->persistenceManager->getIdentifierByObject($notification);
				if (!array_key_exists($registrationIdentifier, $registrationsToNotify)) {
					$registrationsToNotify[$registrationIdentifier] = [
						'registration' => $notification,
						'ticketBookingStateChanged' => [],
						'roomBookingStateChanged' => [],
					];
				}
				$participantIdentifier = $this->persistenceManager->getIdentifierByObject($participant);
				$registrationsToNotify[$registrationIdentifier]['roomBookingStateChanged'][$participantIdentifier] = [
					'participant' => $participant,
					'priorBookingState' => $priorBookingState,
					'newBookingState' => $ticket->getBookingState()
				];
			}
		}
		$this->persistenceManager->persistAll();

		/** @var Registration $registration */
		foreach ($registrationsToNotify as $notification) {
			$this->mailService->sendMoveToWaitingListMail($notification);
		}

	}

	/**
	 * Send email to participants to complete there registration
	 */
	public function sendParticipantCompleteRegistrationMailCommand() {
		$participants = $this->participantRepository->findUncompletedParticipants();
		/** @var Participant $participant */
		foreach ($participants as $participant) {

			$this->mailService->sendParticipantCompleteRegistrationMail($participant);
			$participant->setLastEmailSent(new \DateTime());
			$this->participantRepository->update($participant);
		}
	}

}