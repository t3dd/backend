<?php
namespace T3DD\Backend\Domain\Repository\Registration;

use T3DD\Backend\Domain\Model\Registration\AbstractBookable;
use TYPO3\Flow\Persistence\QueryInterface;

/**
 * Enter descriptions here
 */
abstract class AbstractBookableRepository extends \TYPO3\Flow\Persistence\Doctrine\Repository {

	/**
	 * @var array
	 */
	protected $defaultOrderings = array('date' => QueryInterface::ORDER_ASCENDING);

	/**
	 * @return int
	 */
	public function getSpentQuota() {
		$query = $this->entityManager->createQuery('
			SELECT SUM(b.fraction) fraction
			FROM ' . $this->objectType . ' b
			WHERE b.quotaApplies = true AND b.bookingState != \'' . AbstractBookable::BOOKING_STATE_WAITING . '\'
		');
		return (int) $query->getResult(\Doctrine\ORM\Query::HYDRATE_SINGLE_SCALAR);
	}

	/**
	 * @param int $limit
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findWaitingByCount($limit = 1) {
		$query = $this->createQuery();
		$query->matching($query->equals('bookingState', AbstractBookable::BOOKING_STATE_WAITING));
		$query->setLimit($limit);
		return $query->execute();
	}

}