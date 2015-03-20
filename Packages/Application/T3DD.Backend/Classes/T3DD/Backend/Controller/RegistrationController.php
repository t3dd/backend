<?php
namespace T3DD\Backend\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use T3DD\Backend\Domain\Model\DataTransfer\Registration\Registration as Registration;

class RegistrationController extends \Netlogix\Crud\Controller\RestController {

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'registration';

	/**
	 * @var \T3DD\Backend\Domain\Repository\Registration\RegistrationRepository
	 * @Flow\Inject
	 */
	protected $registrationRepository;

	/**
	 * @param Registration $registration
	 */
	public function createAction(Registration $registration) {
		$this->registrationRepository->add($registration->getPayload());
		$this->view->assign('object', $registration);
	}

	/**
	 * @param Registration $registration
	 */
	public function updateAction(Registration $registration) {
		$this->registrationRepository->update($registration->getPayload());
	}

}
