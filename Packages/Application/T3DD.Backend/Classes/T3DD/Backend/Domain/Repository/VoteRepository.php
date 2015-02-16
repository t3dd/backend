<?php
namespace T3DD\Backend\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use \TYPO3\Flow\Persistence\Doctrine\Repository;

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

	/**
	 * @return array
	 */
	public function getVoteCountForSessions() {
		$query = $this->entityManager->createQuery('SELECT s.Persistence_Object_Identifier session, count(v.Persistence_Object_Identifier) numberOfVotes FROM T3DD\Backend\Domain\Model\Vote v JOIN v.session s GROUP BY v.session');
		return $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
	}

}