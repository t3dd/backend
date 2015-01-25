<?php
namespace T3DD\Backend\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Mate {

	/**
	 * @var string
	 */
	protected $username = '';

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 * @Flow\Validate(type="EmailAddress")
	 */
	protected $email = '';

	/**
	 * @var \Doctrine\Common\Collections\Collection<\T3DD\Backend\Domain\Model\Participant>
	 * @ORM\ManyToMany(mappedBy="roomMates")
	 */
	protected $participants;

	public function __construct() {
		$this->participants = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername($username) {
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

}