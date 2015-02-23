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
	protected $payload;

	/**
	 * @Flow\Inject
	 * @var \Netlogix\Crud\Domain\Service\DataTransferObjectFactory
	 */
	protected $dataTransferObjectFactory;

	/**
	 * @return array<string>
	 */
	public function getPropertyNamesToBeApiExposed() {
		return array('resource', 'title', 'description', 'speakers', 'date', 'voteUri');
	}

	/**
	 * @return \T3DD\Backend\Domain\Model\Session
	 */
	public function getPayload() {
		return $this->payload;
	}

	/**
	 * @return \Netlogix\Crud\Domain\Model\DataTransfer\UriPointer
	 */
	public function getResource() {
		return new \Netlogix\Crud\Domain\Model\DataTransfer\UriPointer(array(
			'packageKey' => 'T3DD.Backend',
			'controllerName' => 'Session',
			'actionName' => 'index',
			'arguments' => array('session' => $this->payload),
		));
	}

	/**
	 * @return Speaker[]
	 */
	public function getSpeakers() {
		return $this->dataTransferObjectFactory->getDataTransferObjects($this->getPayload()->getSpeakers());
	}

	/**
	 * @return \Netlogix\Crud\Domain\Model\DataTransfer\UriPointer
	 */
	public function getVoteUri() {
		return new \Netlogix\Crud\Domain\Model\DataTransfer\UriPointer(array(
			'packageKey' => 'T3DD.Backend',
			'controllerName' => 'Vote',
			'actionName' => 'index',
			'arguments' => array('session' => $this->payload),
		));
	}

}