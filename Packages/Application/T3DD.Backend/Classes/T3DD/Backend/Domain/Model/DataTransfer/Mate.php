<?php
namespace T3DD\Backend\Domain\Model\DataTransfer;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Scope("prototype")
 * @Flow\Entity
 */
class Mate extends \Netlogix\Crud\Domain\Model\DataTransfer\AbstractDataTransferObject {

	/**
	 * @var \T3DD\Backend\Domain\Model\Mate
	 * @ORM\OneToOne
	 */
	protected $innermostSelf;

	/**
	 * @return array<string>
	 */
	public function getPropertyNamesToBeApiExposed() {
		return array('username', 'name', 'email');
	}

	/**
	 * @return \T3DD\Backend\Domain\Model\Mate
	 */
	public function getInnermostSelf() {
		return $this->innermostSelf;
	}

}