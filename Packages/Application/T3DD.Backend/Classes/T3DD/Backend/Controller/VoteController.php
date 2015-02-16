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
	 * @var string
	 */
	protected $resourceArgumentName = 'session';

	public function listAction() {

	}

	public function myVotesAction() {

	}

	/**
	 * @param \T3DD\Backend\Domain\Model\Session $session
	 * @Flow\Validate(argumentName="session", type="T3DD\Backend\Validation\Validator\UniqueVoteValidator")
	 */
	public function createAction(\T3DD\Backend\Domain\Model\Session $session) {
		/** @var \T3DD\Backend\Domain\Model\Vote $vote */
		$vote = $this->objectManager->get('T3DD\\Backend\\Domain\\Model\\Vote');
		$vote->setSession($session);
		$vote->setAccount($this->securityContext->getAccount());
		$this->voteRepository->add($vote);
		$this->response->setStatus(201);
		return;
	}

	/**
	 * @param \T3DD\Backend\Domain\Model\Session $session
	 */
	public function deleteAction(\T3DD\Backend\Domain\Model\Session $session) {
		$account = $this->securityContext->getAccount();
		/** @var \T3DD\Backend\Domain\Model\Vote $vote */
		$vote = $this->voteRepository->getVoteForAccountAndSession($session, $account);

		if (!$vote) {
			$this->response->setStatus(404);
			return;
		}

		if ($vote->getAccount() !== $account && !$this->securityContext->hasRole('T3DD.Backend:Administrator')) {
			$this->response->setStatus(403);
			return;
		}
		$this->voteRepository->remove($vote);
	}

}