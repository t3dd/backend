<?php
namespace T3DD\Backend\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\QueryInterface;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class ValueRepository extends Repository {

	protected $defaultOrderings = array('value' => QueryInterface::ORDER_DESCENDING);

}