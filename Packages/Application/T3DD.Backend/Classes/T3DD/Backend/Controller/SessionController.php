<?php
namespace T3DD\Backend\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

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
	 * Name of the action method argument which acts as the resource for the
	 * RESTful controller. If an argument with the specified name is passed
	 * to the controller, the show, update and delete actions can be triggered
	 * automatically.
	 *
	 * @var string
	 */
	protected $resourceArgumentName = 'session';

	public function listAction() {
		$this->view->assign('value', $this->dataTransferObjectFactory->getDataTransferObjects($this->sessionRepository->findAll()));
	}

	/**
	 * @param \T3DD\Backend\Domain\Model\Session $session
	 */
	public function showAction($session) {
		$this->view->assign('value', $this->dataTransferObjectFactory->getDataTransferObject($session));
	}

	/**
	 * @param \T3DD\Backend\Domain\Model\Session $session
	 */
	public function createAction($session) {
		$this->sessionRepository->add($session);
		$this->persistenceManager->persistAll();
		$this->redirect('index', NULL, NULL, array('session' => $session));
	}

	/**
	 * @param \T3DD\Backend\Domain\Model\Session $session
	 */
	public function updateAction($session) {
		$this->sessionRepository->update($session);
		$this->persistenceManager->persistAll();
		$this->redirect('index', NULL, NULL, array('session' => $session));
	}

	/**
	 * @param \T3DD\Backend\Domain\Model\Session $session
	 */
	public function deleteAction($session) {
		$this->sessionRepository->remove($session);
	}

}