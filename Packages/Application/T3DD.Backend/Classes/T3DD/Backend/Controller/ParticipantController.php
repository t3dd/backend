<?php
namespace T3DD\Backend\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class ParticipantController extends \Netlogix\Crud\Controller\RestController {

	/**
	 * @Flow\Inject
	 * @var \T3DD\Backend\Domain\Repository\ParticipantRepository
	 */
	protected $participantRepository;

	/**
	 * @Flow\Inject
	 * @var \Netlogix\Crud\Domain\Service\DataTransferObjectFactory
	 */
	protected $dataTransferObjectFactory;

	/**
	 * Name of the action method argument which acts as the resource for the
	 * RESTful controller. If an argument with the specified name is passed
	 * to the controller, the show, update and delete actions can be triggered
	 * automatically.
	 *
	 * @var string
	 */
	protected $resourceArgumentName = 'participant';

	public function listAction() {
		$this->view->assign('value', $this->dataTransferObjectFactory->getDataTransferObjects($this->participantRepository->findAll()));
	}

	/**
	 * @param \T3DD\Backend\Domain\Model\Participant $participant
	 */
	public function showAction($participant) {
		$this->view->assign('value', $this->dataTransferObjectFactory->getDataTransferObject($participant));
	}

	public function initializeCreateAction() {
		parent::initializeCreateAction();

		/** @var \TYPO3\Flow\Mvc\Controller\Argument $argument */
		$argument = $this->arguments[$this->resourceArgumentName];

		$configuration = $argument->getPropertyMappingConfiguration()->forProperty('roomMates');
		$configuration->allowAllProperties();
	}

	/**
	 * @param \T3DD\Backend\Domain\Model\Participant $participant
	 */
	public function createAction($participant) {
		$this->participantRepository->add($participant);
		$this->persistenceManager->persistAll();
		$this->redirect('index', NULL, NULL, array('participant' => $participant));
	}

	/**
	 * @param \T3DD\Backend\Domain\Model\Participant $participant
	 */
	public function updateAction($participant) {
		$this->participantRepository->update($participant);
		$this->persistenceManager->persistAll();
		$this->redirect('index', NULL, NULL, array('participant' => $participant));
	}

	/**
	 * @param \T3DD\Backend\Domain\Model\Participant $participant
	 */
	public function deleteAction($participant) {
		$this->participantRepository->remove($participant);
	}

}