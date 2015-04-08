<?php
namespace T3DD\Backend\Domain\Model\DataTransfer\Edit;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Netlogix\Crud\Domain\Model\DataTransfer\UriPointer as UriPointer;

class Session extends \T3DD\Backend\Domain\Model\DataTransfer\Session {

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * @return array
	 */
	public function getThemes() {
		$themes = [];
		foreach ($this->getPayload()->getThemes() as $theme) {
			$themes[] = $this->persistenceManager->getIdentifierByObject($theme);
		}
		return $themes;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->persistenceManager->getIdentifierByObject($this->payload->getType());
	}

	/**
	 * @return string
	 */
	public function getExpertiseLevel() {
		return $this->persistenceManager->getIdentifierByObject($this->payload->getExpertiseLevel());
	}

	/**
	 * @return UriPointer
	 */
	public function getVoteUri() {
		return new UriPointer(array(
			'packageKey' => 'T3DD.Backend',
			'controllerName' => 'Vote',
			'actionName' => 'index',
			'arguments' => array('session' => $this->payload),
		));
	}

}
