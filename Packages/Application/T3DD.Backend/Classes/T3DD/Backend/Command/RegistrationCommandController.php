<?php
namespace T3DD\Backend\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use T3DD\Backend\Domain\Model\Registration\Registration;
use T3DD\Backend\Domain\Repository\Registration\RegistrationRepository;
use T3DD\Backend\Domain\Repository\Registration\ParticipantRepository;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;

class RegistrationCommandController extends CommandController {

	/**
	 * @var RegistrationRepository
	 * @Flow\Inject
	 */
	protected $registrationRepository;

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

}