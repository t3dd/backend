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
	 * @var \T3DD\Backend\Domain\Repository\Registration\RegistrationRepository
	 * @Flow\Inject
	 */
	protected $registrationRepository;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject∂
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
		$configuration->allowProperties('rate', 'roomSize');
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

		$registrationEntity = $registration->getPayload();
		$registrationEntity->setAccount($this->securityContext->getAccount());

		$ticketRequest = [];
		$roomRequests = [];
		/** @var \T3DD\Backend\Domain\Model\Registration\Participant $participant */
		foreach ($registrationEntity->getParticipants() as $participant) {
			$ticketRequest[] = $participant->getTicketRequest();
			$participant->setRegistration($registrationEntity);
			$roomRequest = $participant->getRoomRequest();
			if ($roomRequest !== NULL) {
				$roomRequests[] = $roomRequest;
			}
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
		$configuration->allowAllProperties();
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
		if (!$registration->getPayload()->isCompleted()) {
			$registration->getPayload()->setCompleted(TRUE);
			$this->objectManager->get(\T3DD\Backend\Domain\Service\MailService::class)->sendRegistrationCompletedMail($registration->getPayload()->getBillingAddress());
		}
		$this->registrationRepository->update($registration->getPayload());

		$this->view->assign('value', $registration);
	}

}
