<?php
namespace T3DD\Backend\Domain\Service;

use T3DD\Backend\Domain\Model\Registration\BillingAddress;
use T3DD\Backend\Domain\Model\Registration\Participant;
use T3DD\Backend\Domain\Model\Registration\Registration;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\View\StandaloneView;

/**
 * @Flow\Scope("singleton")
 */
class MailService {

	/**
	 * @var array
	 * @Flow\InjectConfiguration("Registration.Mail")
	 */
	protected $configuration;

	/**
	 * @param Registration $registration
	 * @return int
	 */
	public function sendRegistrationCompletedMail(Registration $registration) {
		return $this->send('registrationCompleted', $registration);
	}

	/**
	 * @param Participant $participant
	 * @return int
	 */
	public function sendParticipantCompleteRegistrationMail(Participant $participant) {
		return $this->send('participantCompleteRegistration', $participant);
	}

	/**
	 * @param array $notification
	 * @return int
	 */
	public function sendMoveToWaitingListMail(array $notification) {
		return $this->send('moveToWaitingList', $notification);
	}

	/**
	 * @param string $purpose
	 * @param object $object
	 * @return int
	 */
	public function send($purpose, $object) {
		$message = new \TYPO3\SwiftMailer\Message();
		$message->setSubject($this->getSubject($purpose))
			->setFrom($this->getSender())
			->setTo($this->getRecipient($object))
			->setBody($this->renderContent($purpose, array('value' => $object)), 'text/html');

		if ($purpose !== 'participantCompleteRegistration' && isset($this->configuration['Bcc'])) {
			$message->setBcc([$this->configuration['Bcc']['email'] => $this->configuration['Bcc']['name']]);
		}

		return $message->send();
	}

	/**
	 * @param string $purpose
	 * @return string
	 */
	protected function getSubject($purpose) {
		if (isset($this->configuration['Subjects'][$purpose])) {
			return $this->configuration['Subjects'][$purpose];
		}

		return $this->configuration['Subjects']['default'];
	}

	/**
	 * @return array
	 */
	protected function getSender() {
		return [$this->configuration['Sender']['email'] => $this->configuration['Sender']['name']];
	}

	/**
	 * @param object $object
	 * @return array
	 */
	protected function getRecipient($object) {
		$recipient = [];
		if ($object instanceof BillingAddress || $object instanceof Participant) {
			$recipient = [$object->getEmail() => $object->getName()];
		} elseif ($object instanceof Registration) {
			$recipient = $this->getRecipient($object->getBillingAddress());
		}
		return $recipient;
	}

	/**
	 * @param string $templateName
	 * @param array $viewVariables
	 * @return string
	 */
	protected function renderContent($templateName, $viewVariables) {
		$view = new StandaloneView();

		$view->getRequest()->getHttpRequest()->setBaseUri($this->configuration['BaseUrl']);
		$view->setLayoutRootPath('resource://T3DD.Backend/Private/Layouts/');
		$view->setTemplatePathAndFilename('resource://T3DD.Backend/Private/Templates/Mail/' . ucfirst($templateName) . '.html');
		$view->setFormat('html');
		$view->assignMultiple($viewVariables);
		return $view->render();
	}
}