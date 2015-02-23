<?php
namespace T3DD\Backend\Validation\Validator;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class UniqueSessionTitleValidator extends \TYPO3\Flow\Validation\Validator\AbstractValidator {

	/**
	 * @Flow\Inject
	 * @var \T3DD\Backend\Domain\Repository\SessionRepository
	 */
	protected $sessionRepository;

	/**
	 * @param \T3DD\Backend\Domain\Model\Session $session
	 * @return void
	 */
	protected function isValid($session) {
		if ($this->sessionRepository->countByTitle($session->getTitle())) {
			$this->addError('There is already a session with this title', 1424730006);
		}

	}

}