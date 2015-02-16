<?php
namespace T3DD\Backend\Validation\Validator;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class UniqueVoteValidator extends \TYPO3\Flow\Validation\Validator\AbstractValidator {

	/**
	 * @Flow\Inject
	 * @var \T3DD\Backend\Domain\Repository\VoteRepository
	 */
	protected $voteRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @param \T3DD\Backend\Domain\Model\Session $session
	 * @return void
	 */
	protected function isValid($session) {
		if ($this->voteRepository->hasUserVotedForSession($session, $this->securityContext->getAccount())) {
			$this->addError('You have already voted for that session', 1424114090);
		}
	}

}