<?php
namespace T3DD\Backend\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use Netlogix\Crud\Controller\RestController;
use T3DD\Backend\Domain\Model\DataTransfer\Participant;
use T3DD\Backend\Domain\Repository\ParticipantRepository;
use TYPO3\Flow\Annotations as Flow;

class ParticipantController extends RestController {

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'participant';

	/**
	 * @var ParticipantRepository
	 * @Flow\Inject
	 */
	protected $participantRepository;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @param Participant $participant
	 */
	public function showAction(Participant $participant) {
		$participantEntity = $participant->getPayload();
		if ($participantEntity->getAccount() !== NULL && $participantEntity->getAccount() !== $this->securityContext->getAccount() && !$this->securityContext->hasRole('T3DD.Backend:Administrator')) {
			$this->response->setStatus(403);
			return;
		}
		$this->view->assign('value', $participant);
	}

	/**
	 * @param Participant $participant
	 */
	public function updateAction(Participant $participant) {
		$participantEntity = $participant->getPayload();
		if ($participantEntity->getAccount() !== NULL && $participantEntity->getAccount() !== $this->securityContext->getAccount() && !$this->securityContext->hasRole('T3DD.Backend:Administrator')) {
			$this->response->setStatus(403);
			return;
		}
		if (!$participantEntity->isCompleted()) {
			$participantEntity->setCompleted(TRUE);
			$participantEntity->setAccount($this->securityContext->getAccount());
		}
		$this->participantRepository->update($participantEntity);
		$this->view->assign('value', $participant);
	}

}