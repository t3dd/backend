<?php
namespace T3DD\Backend\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class VoteController extends \Netlogix\Crud\Controller\RestController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @Flow\Inject
	 * @var \T3DD\Backend\Domain\Repository\VoteRepository
	 */
	protected $voteRepository;

	/**
	 * @Flow\Inject
	 * @var \Netlogix\Crud\Domain\Service\DataTransferObjectFactory
	 */
	protected $dataTransferObjectFactory;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'session';

	/**
	 * Implementation of the arguments initialization in the action controller:
	 * Automatically registers arguments of the current action
	 *
	 * Don't override this method - use initializeAction() instead.
	 *
	 * @return void
	 * @throws \TYPO3\Flow\Mvc\Exception\InvalidArgumentTypeException
	 * @see initializeArguments()
	 */
	public function initializeActionMethodArguments() {
		\TYPO3\Flow\Mvc\Controller\RestController::initializeActionMethodArguments();
	}

	public function listAction() {
		$sessionVotes = $this->voteRepository->getVoteCountForSessions();
		foreach ($sessionVotes as $index => $sessionVote) {
			$sessionVotes[$index]['session'] = new \Netlogix\Crud\Domain\Model\DataTransfer\UriPointer(array(
				'packageKey' => 'T3DD.Backend',
				'controllerName' => 'Session',
				'actionName' => 'index',
				'arguments' => array('session' => $sessionVote['session']),
			));
		}

		$this->view->assign('value', $sessionVotes);
	}

	public function myVotesAction() {
		$this->view->assign('value', $this->dataTransferObjectFactory->getDataTransferObjects($this->voteRepository->findByAccount($this->securityContext->getAccount())));
	}

	/**
	 * @param string $session
	 */
	public function createAction($session) {
		/** @var \T3DD\Backend\Domain\Model\Vote $vote */
		$vote = $this->objectManager->get('T3DD\\Backend\\Domain\\Model\\Vote');
		$vote->setSession($session);
		$vote->setAccount($this->securityContext->getAccount());
		$this->voteRepository->add($vote);
		$this->reportSuccess($vote, 201);
	}

	/**
	 * @param string $session
	 */
	public function deleteAction($session) {
		$account = $this->securityContext->getAccount();
		/** @var \T3DD\Backend\Domain\Model\Vote $vote */
		$vote = $this->voteRepository->getVoteForAccountAndSession($session, $account);

		if (!$vote) {
			$this->response->setStatus(404);
			return;
		}
		$this->voteRepository->remove($vote);
	}

}