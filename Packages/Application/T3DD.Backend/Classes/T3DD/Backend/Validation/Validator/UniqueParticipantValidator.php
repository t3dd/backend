<?php
namespace T3DD\Backend\Validation\Validator;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class UniqueParticipantValidator extends \TYPO3\Flow\Validation\Validator\AbstractValidator {

	/**
	 * @Flow\Inject
	 * @var \T3DD\Backend\Domain\Repository\ParticipantRepository
	 */
	protected $participantRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @param \T3DD\Backend\Domain\Model\Participant $participant
	 * @return void
	 */
	protected function isValid($participant) {
		if ($this->participantRepository->findOneByAccount($this->securityContext->getAccount())) {
			$this->addError('There is already a registration for this account', 1422907478);
		}

	}

}