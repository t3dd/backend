<?php
namespace T3DD\Backend\Tests\Functional\Registration;

use T3DD\Backend\Domain\Model\Registration\BillingAddress;
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
		$sent = $mailService->sendRegistrationCompletedMail($this->createBillingAddress());
		$this->assertEquals(1, $sent);
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

}