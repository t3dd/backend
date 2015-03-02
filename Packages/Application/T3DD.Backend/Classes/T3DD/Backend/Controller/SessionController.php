<?php
namespace T3DD\Backend\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use T3DD\Backend\Domain\Model\Session;
use TYPO3\Flow\Annotations as Flow;

class SessionController extends \Netlogix\Crud\Controller\RestController {

	/**
	 * @Flow\Inject
	 * @var \T3DD\Backend\Domain\Repository\SessionRepository
	 */
	protected $sessionRepository;

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
	 * Name of the action method argument which acts as the resource for the
	 * RESTful controller. If an argument with the specified name is passed
	 * to the controller, the show, update and delete actions can be triggered
	 * automatically.
	 *
	 * @var string
	 */
	protected $resourceArgumentName = 'session';

	/**
	 *
	 */
	public function listAction() {
		$this->view->assign('value', $this->dataTransferObjectFactory->getDataTransferObjects($this->sessionRepository->findAll()));
	}

	/**
	 * @param Session $session
	 */
	public function showAction(Session $session) {
		$this->response->getHeaders()->setCacheControlDirective('no-store');
		$this->view->assign('value', $this->dataTransferObjectFactory->getDataTransferObject($session));
	}

	public function initializeCreateAction() {
		parent::initializeCreateAction();

		/** @var \TYPO3\Flow\Mvc\Controller\Argument $argument */
		$argument = $this->arguments['session'];
		$configuration = $argument->getPropertyMappingConfiguration()->forProperty('themes.*');
		$configuration->allowAllProperties();
		$configuration->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);
	}

	/**
	 * @param Session $session
	 * @Flow\Validate(argumentName="session", type="T3DD\Backend\Validation\Validator\UniqueSessionTitleValidator")
	 */
	public function createAction(Session $session) {
		$session->setAccount($this->securityContext->getAccount());
		$this->sessionRepository->add($session);
		$this->reportSuccess($session, 201);
	}

	/**
	 * @param Session $session
	 */
	public function updateAction(Session $session) {
		$this->sessionRepository->update($session);
		$this->reportSuccess($session);
	}

	/**
	 * @param Session $session
	 */
	public function deleteAction(Session $session) {
		$this->sessionRepository->remove($session);
	}

}
