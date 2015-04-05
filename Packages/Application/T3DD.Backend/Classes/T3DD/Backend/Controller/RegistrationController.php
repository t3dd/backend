<?php
namespace T3DD\Backend\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use T3DD\Backend\Domain\Model\DataTransfer\Registration\Registration as Registration;

class RegistrationController extends \Netlogix\Crud\Controller\RestController {

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'registration';

	/**
	 * @var \T3DD\Backend\Domain\Service\Registration\BookableService
	 * @Flow\Inject
	 */
	protected $bookableService;

	/**
	 * @var \TYPO3\Party\Domain\Service\PartyService
	 * @Flow\Inject
	 */
	protected $partyService;

	/**
	 * @var \T3DD\Backend\Domain\Repository\Registration\RegistrationRepository
	 * @Flow\Inject
	 */
	protected $registrationRepository;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	public function pendingAction() {
		$registration = $this->registrationRepository->findOneByAccountAndNotCompleted($this->securityContext->getAccount());
		if ($registration !== NULL) {
			$this->view->assign('value', $this->dataTransferObjectFactory->getDataTransferObject($registration));
		} else {
			$this->response->setStatus(404);
			$this->view->assign('value', NULL);
		}
	}

	public function statusAction() {
		$this->view->assign('value', array('tickets' => $this->bookableService->getTicketsStatus(), 'rooms' => $this->bookableService->getRoomStatus()));
	}

	/**
	 * @param \T3DD\Backend\Domain\Model\Registration\Registration $registration
	 */
	public function showAction(\T3DD\Backend\Domain\Model\Registration\Registration $registration) {
		if ($registration->getAccount() !== $this->securityContext->getAccount() && !$this->securityContext->hasRole('T3DD.Backend:Administrator')) {
			$this->response->setStatus(403);
			return;
		}
		$this->view->assign('value', $this->dataTransferObjectFactory->getDataTransferObject($registration));
	}

	/**
	 *
	 */
	public function initializeCreateAction() {
		parent::initializeCreateAction();

		/** @var \TYPO3\Flow\Mvc\Controller\Argument $argument */
		$argument = $this->arguments[$this->resourceArgumentName];
		$configuration = $argument->getPropertyMappingConfiguration()->forProperty('participants.*');
		$configuration->allowProperties('isRegistrant', 'rate', 'roomSize');
		$configuration->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter',
			\TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED,
			TRUE);
	}

	/**
	 * @param Registration $registration
	 */
	public function createAction(Registration $registration) {
		$pendingRegistration = $this->registrationRepository->findOneByAccountAndNotCompleted($this->securityContext->getAccount());
		if ($pendingRegistration !== NULL) {
			$this->view->assign('value', $this->dataTransferObjectFactory->getDataTransferObject($pendingRegistration));
			return;
		}
		$registrationLock = new \TYPO3\Flow\Utility\Lock\Lock('T3DD-Registration');

		$account = $this->securityContext->getAccount();

		$registrationEntity = $registration->getPayload();
		$registrationEntity->setAccount($account);

		$ticketRequest = [];
		$roomRequests = [];
		$participantPosition = 0;
		/** @var \T3DD\Backend\Domain\Model\Registration\Participant $participant */
		foreach ($registrationEntity->getParticipants() as $participant) {
			$participant->setPositionInRegistration($participantPosition++);
			$participant->setRegistration($registrationEntity);
			if ($participant->isRegistrant()) {
				$person = $this->partyService->getAssignedPartyOfAccount($account);
				$participant->setAccount($account);
				$participant->setPerson($person);
			}

			$ticketRequest[] = $participant->getTicketRequest();
			$roomRequest = $participant->getRoomRequest();
			if ($roomRequest !== NULL) {
				$roomRequests[] = $roomRequest;
			};
		}
		$this->bookableService->requestTickets($ticketRequest);
		$this->bookableService->requestRooms($roomRequests);

		$this->registrationRepository->add($registrationEntity);
		$this->persistenceManager->persistAll();

		$registrationLock->release();

		$this->view->assign('value', $registration);
	}

	/**
	 *
	 */
	public function initializeUpdateAction() {
		parent::initializeUpdateAction();

		/** @var \TYPO3\Flow\Mvc\Controller\Argument $argument */
		$argument = $this->arguments[$this->resourceArgumentName];
		$configuration = $argument->getPropertyMappingConfiguration()->forProperty('participants.*');
		$configuration->skipUnknownProperties();
		$configuration->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter',
			\TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED,
			TRUE);
		$configuration = $argument->getPropertyMappingConfiguration()->forProperty('billingAddress');
		$configuration->allowAllProperties();
		$configuration->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter',
			\TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED,
			TRUE);
	}

	/**
	 * @param Registration $registration
	 */
	public function updateAction(Registration $registration) {
		$registrationEntity = $registration->getPayload();
		if (!$registrationEntity->isCompleted()) {
			$registrationEntity->setCompleted(TRUE);
			$this->objectManager->get(\T3DD\Backend\Domain\Service\MailService::class)->sendRegistrationCompletedMail($registrationEntity->getBillingAddress());
		}
		/** @var \T3DD\Backend\Domain\Model\Registration\Participant $participant */
		foreach ($registrationEntity->getParticipants() as $participant) {
			if ($participant->isRegistrant()) {
				$participant->setCompleted(TRUE);
			}
		}
		$this->registrationRepository->update($registrationEntity);

		$this->view->assign('value', $registration);
	}

}
