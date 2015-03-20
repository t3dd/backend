<?php
namespace T3DD\Backend\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use Doctrine\Common\Collections\Collection;
use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Session {

	/**
	 * @var \DateTime
	 */
	protected $date;

	/**
	 * @var \TYPO3\Flow\Security\Account
	 * @ORM\ManyToOne
	 */
	protected $account = NULL;

	/**
	 * @var string
	 * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=120 })
	 * @Flow\Identity
	 * @ORM\Column(length=120)
	 */
	protected $title;

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 * @Flow\Validate(type="Raw")
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $description;

	/**
	 * @var Collection<\T3DD\Backend\Domain\Model\Value>
	 * @ORM\ManyToMany
	 */
	protected $themes;

	/**
	 * @var \T3DD\Backend\Domain\Model\Value
	 * @ORM\ManyToOne
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $type;

	/**
	 * @var \T3DD\Backend\Domain\Model\Value
	 * @ORM\ManyToOne
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $expertiseLevel;

	public function __construct() {
		$this->date = new \DateTime();
		$this->themes = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @return \TYPO3\Flow\Security\Account
	 */
	public function getAccount() {
		return $this->account;
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 */
	public function setAccount($account) {
		$this->account = $account;
	}

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
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @param \DateTime $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * @return Collection<\T3DD\Backend\Domain\Model\Value>
	 */
	public function getThemes() {
		return $this->themes;
	}

	/**
	 * @param Collection $themes
	 * @internal param Collection $theme
	 */
	public function setThemes(Collection $themes) {
		$this->themes = $themes;
	}

	/**
	 * @return Value
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param Value $type
	 */
	public function setType(Value $type) {
		$this->type = $type;
	}

	/**
	 * @return Value
	 */
	public function getExpertiseLevel() {
		return $this->expertiseLevel;
	}

	/**
	 * @param Value $expertiseLevel
	 */
	public function setExpertiseLevel(Value $expertiseLevel) {
		$this->expertiseLevel = $expertiseLevel;
	}

}