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
class Vote extends \Netlogix\Crud\Domain\Model\DataTransfer\AbstractDataTransferObject {

	/**
	 * @var \T3DD\Backend\Domain\Model\Vote
	 * @ORM\OneToOne
	 */
	protected $payload;

	/**
	 * @return array<string>
	 */
	public function getPropertyNamesToBeApiExposed() {
		return array('session', 'date', 'voteUri');
	}

	/**
	 * @return \T3DD\Backend\Domain\Model\Vote
	 */
	public function getPayload() {
		return $this->payload;
	}

	/**
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->getPayload()->getDate()->getTimestamp();
	}

	/**
	 * @return \Netlogix\Crud\Domain\Model\DataTransfer\UriPointer
	 */
	public function getSession() {
		return new \Netlogix\Crud\Domain\Model\DataTransfer\UriPointer(array(
			'packageKey' => 'T3DD.Backend',
			'controllerName' => 'Vote',
			'actionName' => 'index',
			'arguments' => array('session' => $this->payload->getSession()),
		));
	}

	/**
	 * @return \Netlogix\Crud\Domain\Model\DataTransfer\UriPointer
	 */
	public function getVoteUri() {
		return new \Netlogix\Crud\Domain\Model\DataTransfer\UriPointer(array(
			'packageKey' => 'T3DD.Backend',
			'controllerName' => 'Vote',
			'actionName' => 'index',
			'arguments' => array('session' => $this->payload->getSession()),
		));
	}

}