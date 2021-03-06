<?php
namespace T3DD\Backend\Domain\Model\DataTransfer;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Netlogix\Crud\Domain\Model\DataTransfer\UriPointer as UriPointer;

class Session extends \Netlogix\Crud\Domain\Model\DataTransfer\AbstractDataTransferObject {

	/**
	 * @var \T3DD\Backend\Domain\Model\Session
	 */
	protected $payload;

	/**
	 * @Flow\Inject
	 * @var \Netlogix\Crud\Domain\Service\DataTransferObjectFactory
	 */
	protected $dataTransferObjectFactory;

	/**
	 * @param \T3DD\Backend\Domain\Model\Session $payload
	 */
	public function __construct($payload) {
		parent::__construct($payload);
	}

	/**
	 * @return array<string>
	 */
	public function getPropertyNamesToBeApiExposed() {
		return array('resource', 'creator', 'title', 'description', 'themes', 'type', 'expertiseLevel', 'date', 'voteUri', 'identifier');
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->payload->setTitle($title);
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->payload->setTitle($description);
	}

	/**
	 * @param Collection $themes
	 */
	public function setThemes(Collection $themes) {
		$this->payload->setThemes($themes);
	}

	/**
	 * @return \T3DD\Backend\Domain\Model\Session
	 */
	public function getPayload() {
		return $this->payload;
	}

	/**
	 * @return UriPointer
	 */
	public function getResource() {
		return new UriPointer(array(
			'packageKey' => 'T3DD.Backend',
			'controllerName' => 'Session',
			'actionName' => 'index',
			'arguments' => array('session' => $this->payload),
		));
	}

	/**
	 * @return Account
	 */
	public function getCreator() {
		$account = $this->payload->getAccount();
		if ($account !== NULL) {
			return $this->objectManager->get(Account::class, $account);
		} else {
			return NULL;
		}
	}

	/**
	 * @return array
	 */
	public function getThemes() {
		return $this->dataTransferObjectFactory->getDataTransferObjects($this->getPayload()->getThemes());
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->payload->getType()->getTitle();
	}

	/**
	 * @return string
	 */
	public function getExpertiseLevel() {
		return $this->payload->getExpertiseLevel()->getTitle();
	}

	/**
	 * @return mixed
	 */
	public function getIdentifier() {
		return $this->Persistence_Object_Identifier;
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
