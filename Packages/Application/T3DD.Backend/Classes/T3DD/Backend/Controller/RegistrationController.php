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
	 * @param Registration $registration
	 */
	public function updateAction(Registration $registration) {
		$this->registrationRepository->update($registration->getPayload());
	}

}
