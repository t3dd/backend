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
	 * @var \TYPO3\Flow\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @Flow\Inject
	 * @var \Netlogix\Crud\Domain\Service\DataTransferObjectFactory
	 */
	protected $dataTransferObjectFactory;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Policy\PolicyService
	 */
	protected $policyService;

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
		if ($participant->getAccount() !== $this->securityContext->getAccount() && !$this->securityContext->hasRole('T3DD.Backend:Administrator')) {
			$this->response->setStatus(403);
			return;
		}
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
	 * @Flow\Validate(argumentName="participant", type="T3DD\Backend\Validation\Validator\UniqueParticipantValidator")
	 */
	public function createAction($participant) {
		$account = $this->securityContext->getAccount();
		$account->setRoles(array(
			$this->policyService->getRole('T3DD.Backend:Participant')
		));
		$participant->setAccount($account);
		$this->participantRepository->add($participant);
		$this->accountRepository->update($account);
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
		if ($participant->getAccount() !== $this->securityContext->getAccount() && !$this->securityContext->hasRole('T3DD.Backend:Administrator')) {
			$this->response->setStatus(403);
			return;
		}
		$this->participantRepository->remove($participant);
	}

	public function errorAction() {
		$validationResults = $this->arguments->getValidationResults()->getFlattenedErrors();
		$result = array();
		/** @var \TYPO3\Flow\Error\Error  $validationResult */
		foreach ($validationResults as $key => $validationResult) {
			/** @var \TYPO3\Flow\Validation\Error $error */
			foreach ($validationResult as $error) {
				$result['errors'][$key][] = array(
					'code' => $error->getCode(),
					'message' => $error->getMessage()
				);
			}
		}
		$result['success'] = false;
		$this->view->assign('value', $result);
		$this->response->setStatus(403);
	}

}