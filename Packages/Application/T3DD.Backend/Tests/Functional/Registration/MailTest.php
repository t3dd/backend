<?php
namespace T3DD\Backend\Tests\Functional\Registration;

use T3DD\Backend\Domain\Model\Registration\BillingAddress;
use T3DD\Backend\Domain\Model\Registration\Participant;
use T3DD\Backend\Domain\Model\Registration\Registration;
use TYPO3\Flow\var_dump;

/**
 * Enter descriptions here
 */
class MailTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	public function setUp() {
		parent::setUp();
	}

	/**
	 * @test
	 */
	public function sendRegistrationCompletedMail() {
		/** @var \T3DD\Backend\Domain\Service\MailService $mailService */
		$mailService = $this->objectManager->get(\T3DD\Backend\Domain\Service\MailService::class);
		$sent = $mailService->sendRegistrationCompletedMail($this->createRegistration());
		$this->assertEquals(1, $sent);
	}

	/**
	 * @return Registration
	 */
	protected function createRegistration() {
		$registration = new Registration();
		$registration->setBillingAddress($this->createBillingAddress());
		$registration->addParticipant($this->createFullParticipant());
		$registration->addParticipant($this->createParticipant());
		return $registration;
	}

	/**
	 * @return BillingAddress
	 */
	protected function createBillingAddress() {
		$faker = \Faker\Factory::create('de_DE');
		$billingAddress = new BillingAddress();
		$billingAddress->setName($faker->name);
		$billingAddress->setCompanyName($faker->company);
		$billingAddress->setStreet($faker->streetAddress);
		$billingAddress->setZip($faker->postcode);
		$billingAddress->setCity($faker->city);
		$billingAddress->setCountry($faker->country);
		$billingAddress->setEmail($faker->email);
		return $billingAddress;
	}

	/**
	 * @return Participant
	 */
	protected function createParticipant() {
		$faker = \Faker\Factory::create('de_DE');
		$participant = new Participant();
		$participant->setName($faker->name);
		$participant->setEmail($faker->email);
		$participant->setCompanyName($faker->company);
		$participant->setCountry($faker->country);
		return $participant;
	}

	/**
	 * @return Participant
	 */
	protected function createFullParticipant() {
		$participant = $this->createParticipant();
		$participant->setFoodType('default');
		$participant->setFoodWishes('Feinstes Fleisch aus der Schweinerippe mit original RibWich-Rub trocken über Nacht mariniert, bei Niedrigtemperatur langsam auf dem BBQ-Smoker fertig gegart und auf einem eigens kreierten Brötchen, dem RibWich-Bun, mit leckerer BBQ-Sauce und frischen Toppings vollendet! Zartes saftig gesmoktes Fleisch vom Schwein!');
		$participant->setTshirtType('default');
		$participant->setTshirtSize('XL');
		$participant->setNewcomer(FALSE);
		$participant->setYearExpertise(5);
		$participant->setCompleted(TRUE);
		return $participant;
	}


}