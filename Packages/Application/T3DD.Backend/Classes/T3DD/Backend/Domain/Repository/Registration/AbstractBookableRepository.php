<?php
namespace T3DD\Backend\Domain\Repository\Registration;

/**
 * Enter descriptions here
 */
abstract class AbstractBookableRepository extends \TYPO3\Flow\Persistence\Doctrine\Repository {

	/**
	 * @return int
	 */
	public function getSpentQuota() {
		$query = $this->entityManager->createQuery('
			SELECT SUM(b.fraction) fraction
			FROM ' . $this->objectType . ' b
			WHERE b.quotaApplies = true AND b.bookingState != \'' . \T3DD\Backend\Domain\Model\Registration\AbstractBookable::BOOKING_STATE_WAITING . '\'
		');
		return (int) $query->getResult(\Doctrine\ORM\Query::HYDRATE_SINGLE_SCALAR);
	}

}