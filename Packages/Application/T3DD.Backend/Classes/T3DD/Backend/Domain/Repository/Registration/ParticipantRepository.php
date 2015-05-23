<?php
namespace T3DD\Backend\Domain\Repository\Registration;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use T3DD\Backend\Domain\Model\Registration\AbstractBookable;
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
		$queryConstraints = [];
		$queryConstraints[] = $query->equals('completed', FALSE);
		$queryConstraints[] = $query->logicalNot($query->equals('ticket.bookingState', AbstractBookable::BOOKING_STATE_WAITING));
		$queryConstraints[] = $query->lessThanOrEqual('lastEmailSent', new \DateTime('now - 7 Days'));

		$query->matching($query->logicalAnd($queryConstraints));
		return $query->execute();
	}

}