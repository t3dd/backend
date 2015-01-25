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
class Session extends \Netlogix\Crud\Domain\Model\DataTransfer\AbstractDataTransferObject {

	/**
	 * @var \T3DD\Backend\Domain\Model\Session
	 * @ORM\OneToOne
	 */
	protected $innermostSelf;

	/**
	 * @Flow\Inject
	 * @var \Netlogix\Crud\Domain\Service\DataTransferObjectFactory
	 */
	protected $dataTransferObjectFactory;

	/**
	 * @return array<string>
	 */
	public function getPropertyNamesToBeApiExposed() {
		return array('resource', 'title', 'description', 'speakers');
	}

	/**
	 * @return \T3DD\Backend\Domain\Model\Mate
	 */
	public function getInnermostSelf() {
		return $this->innermostSelf;
	}

	/**
	 * @return \Netlogix\Crud\Domain\Model\DataTransfer\UriPointer
	 */
	public function getResource() {
		return new \Netlogix\Crud\Domain\Model\DataTransfer\UriPointer(array(
			'packageKey' => 'T3DD.Backend',
			'controllerName' => 'Session',
			'actionName' => 'index',
			'arguments' => array('session' => $this->innermostSelf),
		));
	}

	/**
	 * @return Speaker[]
	 */
	public function getSpeakers() {
		return $this->dataTransferObjectFactory->getDataTransferObjects($this->getInnermostSelf()->getSpeakers());
	}

}