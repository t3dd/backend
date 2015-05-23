<?php
namespace T3DD\Backend\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use Netlogix\Crud\Controller\RestController;
use T3DD\Backend\Domain\Model\DataTransfer\Registration\Participant;
use T3DD\Backend\Domain\Repository\Registration\ParticipantRepository;
use TYPO3\Flow\Annotations as Flow;

class ParticipantController extends RestController {

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'participant';

	/**
	 * @var ParticipantRepository
	 * @Flow\Inject
	 */
	protected $participantRepository;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @param Participant $participant
	 */
	public function showAction(Participant $participant) {
		$participantEntity = $participant->getPayload();
		if ($participantEntity->getAccount() !== NULL && $participantEntity->getAccount() !== $this->securityContext->getAccount() && !$this->securityContext->hasRole('T3DD.Backend:Administrator')) {
			$this->response->setStatus(403);
			return;
		}
		if ($participantEntity->getRoomSize() > 0 && $participantEntity->getRoomMates()->count() === 0) {
			for ($i = 1; $i < $participantEntity->getRoomSize(); $i++) {
				$participantEntity->addRoomMate(new \T3DD\Backend\Domain\Model\Registration\Mate());
			}
			$this->participantRepository->update($participantEntity);
			$this->persistenceManager->persistAll();
		}
		$this->view->assign('value', $participant);
	}

	/**
	 *
	 */
	public function initializeUpdateAction() {
		parent::initializeUpdateAction();

		/** @var \TYPO3\Flow\Mvc\Controller\Argument $argument */
		$argument = $this->arguments[$this->resourceArgumentName];
		$configuration = $argument->getPropertyMappingConfiguration()->forProperty('roomMates.*');
		$configuration->allowProperties('name');
		$configuration->skipUnknownProperties();
		$configuration->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter',
			\TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED,
			TRUE);
	}

	/**
	 * @param Participant $participant
	 */
	public function updateAction(Participant $participant) {
		$participantEntity = $participant->getPayload();
		if ($participantEntity->getAccount() !== NULL && $participantEntity->getAccount() !== $this->securityContext->getAccount() && !$this->securityContext->hasRole('T3DD.Backend:Administrator')) {
			$this->response->setStatus(403);
			return;
		}
		if (!$participantEntity->isCompleted()) {
			$participantEntity->setCompleted(TRUE);
			$participantEntity->setAccount($this->securityContext->getAccount());
		}
		$this->participantRepository->update($participantEntity);
		$this->view->assign('value', $participant);
	}

}