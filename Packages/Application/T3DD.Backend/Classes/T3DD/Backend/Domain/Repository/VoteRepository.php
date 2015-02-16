<?php
namespace T3DD\Backend\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class VoteRepository extends Repository {

	/**
	 * @param \T3DD\Backend\Domain\Model\Session $session
	 * @param \TYPO3\Flow\Security\Account $account
	 * @return bool
	 */
	public function hasUserVotedForSession(\T3DD\Backend\Domain\Model\Session $session, \TYPO3\Flow\Security\Account $account) {
		$query = $this->createQuery();
		$query->matching($query->logicalAnd(array($query->equals('session', $session), $query->equals('account', $account))));
		return $query->count() ? TRUE : FALSE;
	}

	/**
	 * @param \T3DD\Backend\Domain\Model\Session $session
	 * @param \TYPO3\Flow\Security\Account $account
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function getVoteForAccountAndSession(\T3DD\Backend\Domain\Model\Session $session, \TYPO3\Flow\Security\Account $account) {
		$query = $this->createQuery();
		$query->matching($query->logicalAnd(array($query->equals('session', $session), $query->equals('account', $account))));
		return $query->execute()->getFirst();
	}

}