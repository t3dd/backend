<?php
namespace T3DD\Backend\Tests\Functional\Registration;

use TYPO3\Flow\Http\Request;
use TYPO3\Flow\Http\Uri;
use TYPO3\Flow\var_dump;

/**
 * Enter descriptions here
 */
class RegistrationTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	const ENDPOINT = 'http://localhost/registration';

	/**
	 * @var boolean
	 */
	protected $testableSecurityEnabled = TRUE;

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	public function setUp() {
		parent::setUp();
		$accountRepository = $this->objectManager->get('\TYPO3\Flow\Security\AccountRepository');
		$accountFactory = $this->objectManager->get('\TYPO3\Flow\Security\AccountFactory');
		$registrant = $accountFactory->createAccountWithPassword('functional_test_account', 'a_very_secure_long_password', array('T3DD.Backend:Authenticated'), 'HttpBasic');
		$accountRepository->add($registrant);
		$this->persistenceManager->persistAll();
	}

	/**
	 * @test
	 */
	public function registrationFailsForUnauthenticatedUser() {
		$request = $this->createRegistrationRequest($this->buildRegistrationCreationPayload());
		$response = $this->browser->sendRequest($request);
		$this->assertEquals('403', $response->getStatusCode());
	}

	/**
	 * @test
	 */
	public function registrationPassesForAuthenticatedUser() {
		$payload = $this->buildRegistrationCreationPayload();
		$request = $this->createRegistrationRequest($payload);
		$request->setHeader('Authorization', 'Basic ' . base64_encode('functional_test_account:a_very_secure_long_password'));
		$response = $this->browser->sendRequest($request);
		$this->assertEquals('200', $response->getStatusCode());
		$this->assertEquals(json_decode($payload, TRUE), json_decode($response->getContent(), TRUE));
	}

	/**
	 * @return string
	 */
	protected function buildRegistrationCreationPayload() {
		return json_encode([
			'participantCount' => 1,
			'participants' => [
				[
					'rate' => 'assoc',
					'roomSize' => 2
				]
			]
		]);
	}

	/**
	 * @return string
	 */
	protected function buildRegistrationUpdatePayload() {
		return json_encode([
			'participantCount' => 1,
			'participants' => [
				[
					'name' => 'Foo Bar FOOOSOSOS',
					'email' => 'foo@example.org',
					'company' => 'Foo Bar Company',
				]
			]
		]);
	}

	/**
	 * @param string $payload
	 * @param string $uri
	 * @return Request
	 */
	protected function createRegistrationRequest($payload, $uri = self::ENDPOINT) {
		$request = Request::create(new Uri($uri));
		$request->setMethod('POST');
		$request->setHeader('Content-Type', 'application/json');
		$request->setHeader('Accept', 'application/json');
		$request->setContent($payload);
		return $request;
	}

}