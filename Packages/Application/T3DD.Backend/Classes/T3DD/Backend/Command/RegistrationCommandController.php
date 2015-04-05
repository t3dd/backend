<?php
namespace T3DD\Backend\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use T3DD\Backend\Domain\Model\Registration\Participant;
use T3DD\Backend\Domain\Model\Registration\Registration;
use T3DD\Backend\Domain\Repository\Registration\RegistrationRepository;
use T3DD\Backend\Domain\Repository\Registration\ParticipantRepository;
use T3DD\Backend\Domain\Service\MailService;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;

class RegistrationCommandController extends CommandController {

	/**
	 * @var RegistrationRepository
	 * @Flow\Inject
	 */
	protected $registrationRepository;

	/**
	 * @var ParticipantRepository
	 * @Flow\Inject
	 */
	protected $participantRepository;

	/**
	 * @var MailService
	 * @Flow\Inject
	 */
	protected $mailService;

	/**
	 * Remove uncompleted registrations
	 */
	public function removeUncompletedRegistrationsCommand() {
		$registrations = $this->registrationRepository->findUncompletedRegistrationsToRemove();
		/** @var Registration $registration */
		foreach ($registrations as $registration) {
			$this->registrationRepository->remove($registration);
		}
	}

	/**
	 * Send email to participants to complete there registration
	 */
	public function sendParticipantCompleteRegistrationMailCommand() {
		return;

		// TODO: Find out how to create absolute urls in cli context!
		$participants = $this->participantRepository->findUncompletedParticipants();
		/** @var Participant $participant */
		foreach ($participants as $participant) {

			$this->mailService->sendParticipantCompleteRegistrationMail($participant);
			$participant->setLastEmailSent(new \DateTime());
			$this->participantRepository->update($participant);
		}
	}

}