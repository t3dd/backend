<?php
namespace T3DD\Backend\Domain\Model\DataTransfer;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

class Vote extends \Netlogix\Crud\Domain\Model\DataTransfer\AbstractDataTransferObject {

	/**
	 * @var \T3DD\Backend\Domain\Model\Vote
	 */
	protected $payload;

	/**
	 * @param \T3DD\Backend\Domain\Model\Vote $payload
	 */
	public function __construct($payload) {
		parent::__construct($payload);
	}

	/**
	 * @return array<string>
	 */
	public function getPropertyNamesToBeApiExposed() {
		return array('date', 'session');
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
	 * @return string
	 */
	public function getSession() {
		return $this->getPayload()->getSession();
	}

}