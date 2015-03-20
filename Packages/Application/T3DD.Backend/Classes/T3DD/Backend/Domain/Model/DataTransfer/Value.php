<?php
namespace T3DD\Backend\Domain\Model\DataTransfer;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Netlogix\Crud\Domain\Model\DataTransfer\UriPointer as UriPointer;

class Value extends \Netlogix\Crud\Domain\Model\DataTransfer\AbstractDataTransferObject {

	/**
	 * @var \T3DD\Backend\Domain\Model\Value
	 */
	protected $payload;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * @param \T3DD\Backend\Domain\Model\Value $payload
	 */
	public function __construct($payload) {
		parent::__construct($payload);
	}

	/**
	 * @return array<string>
	 */
	public function getPropertyNamesToBeApiExposed() {
		return array('resource', 'identifier', 'title', 'type');
	}

	/**
	 * @return UriPointer
	 */
	public function getResource() {
		return new UriPointer(array(
			'packageKey' => 'T3DD.Backend',
			'controllerName' => 'Value',
			'actionName' => 'index',
			'arguments' => array('value' => $this->payload),
		));
	}

	/**
	 * @return string
	 */
	public function getIdentifier() {
		return $this->persistenceManager->getIdentifierByObject($this->payload);
	}

	/**
	 * @return \T3DD\Backend\Domain\Model\Vote
	 */
	public function getPayload() {
		return $this->payload;
	}

}