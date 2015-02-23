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
	 */
	protected $description;

	/**
	 * @var Collection<\T3DD\Backend\Domain\Model\Participant>
	 * @ORM\ManyToMany
	 */
	protected $speakers;

	public function __construct() {
		$this->date = new \DateTime();
		$this->speakers = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @return Collection<\T3DD\Backend\Domain\Model\Participant>
	 */
	public function getSpeakers() {
		return $this->speakers;
	}

	/**
	 * @param Collection $speakers
	 */
	public function setSpeakers(Collection $speakers) {
		$this->speakers = $speakers;
	}

	/**
	 * @param Participant $speaker
	 */
	public function addSpeaker(Participant $speaker) {
		$this->speakers->add($speaker);
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

}