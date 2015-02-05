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
class Participant extends \Netlogix\Crud\Domain\Model\DataTransfer\AbstractDataTransferObject {

	/**
	 * @var \T3DD\Backend\Domain\Model\Participant
	 * @ORM\OneToOne
	 */
	protected $innermostSelf;

	/**
	 * @var string
	 */
	protected $resource;

	/**
	 * @Flow\Inject
	 * @var \Netlogix\Crud\Domain\Service\DataTransferObjectFactory
	 */
	protected $dataTransferObjectFactory;

	/**
	 * @return array<string>
	 */
	public function getPropertyNamesToBeApiExposed() {
		return array('resource', 'date', 'name', 'company', 'street', 'zip', 'city', 'country', 'foodType', 'foodWishes', 'tshirtSize', 'newcomer', 'room', 'roomSize', 'roomMates');
	}

	/**
	 * @return \T3DD\Backend\Domain\Model\Participant
	 */
	public function getInnermostSelf() {
		return $this->innermostSelf;
	}

	/**
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->getInnermostSelf()->getDate()->getTimestamp();
	}

	/**
	 * @return Mate[]
	 */
	public function getRoomMates() {
		return $this->dataTransferObjectFactory->getDataTransferObjects($this->getInnermostSelf()->getRoomMates());
	}

	/**
	 * @return \Netlogix\Crud\Domain\Model\DataTransfer\UriPointer
	 */
	public function getResource() {
		return new \Netlogix\Crud\Domain\Model\DataTransfer\UriPointer(array(
			'packageKey' => 'T3DD.Backend',
			'controllerName' => 'Participant',
			'actionName' => 'index',
			'arguments' => array('participant' => $this->innermostSelf),
		));
	}

}