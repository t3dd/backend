<?php
namespace T3DD\Backend\Domain\Repository\Registration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class ParticipantRepository extends Repository {

	/**
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findUncompletedParticipants() {
		$query = $this->createQuery();
		$queryConstraints = array();
		$queryConstraints[] = $query->equals('completed', FALSE);
		$queryConstraints[] = $query->lessThanOrEqual('lastEmailSent', new \DateTime('now - 7 Days'));

		// Todo: Remove participants with waiting tickets

		$query->matching($query->logicalAnd($queryConstraints));
		return $query->execute();
	}

}