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
class Value {

	/**
	 * @var string
	 * @Flow\Identity
	 * @Flow\Validate(type="Text")
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=80 })
	 * @ORM\Column(length=80)
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->title;
	}

}