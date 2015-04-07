<?php
namespace T3DD\Backend\Domain\Repository\Registration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;
use TYPO3\Flow\Security\Account;

/**
 * @Flow\Scope("singleton")
 */
class RegistrationRepository extends Repository {

	/**
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findUncompletedRegistrationsToRemove() {
		$query = $this->createQuery();
		$queryConstraints = array();
		$queryConstraints[] = $query->equals('completed', FALSE);
		$queryConstraints[] = $query->lessThanOrEqual('date', new \DateTime('now - 30 Minutes'));

		$query->matching($query->logicalAnd($queryConstraints));
		return $query->execute();
	}

	/**
	 * @param Account $account
	 * @return \T3DD\Backend\Domain\Model\Registration\Registration
	 */
	public function findOneByAccountAndNotCompleted(Account $account) {
		$query =$this->createQuery();
		$queryConstraints = array();
		$queryConstraints[] = $query->equals('completed', FALSE);
		$queryConstraints[] = $query->equals('account', $account);

		$query->matching($query->logicalAnd($queryConstraints));
		return $query->execute()->getFirst();
	}

}