<?php

use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\TableNode, Behat\MinkExtension\Context\MinkContext;
use TYPO3\Flow\Utility\Arrays;
use PHPUnit_Framework_Assert as Assert;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

/**
 * Features context
 */
class FeatureContext extends MinkContext {

	/**
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/** @var \T3DD\Backend\Tests\Behat\FlowContext */
	protected $flowContext;

	/**
	 * @var \Behat\WebApiExtension\Context\WebApiContext
	 */
	protected $webApiContext;

	/** @BeforeScenario */
	public function gatherContexts(BeforeScenarioScope $scope) {
		$environment = $scope->getEnvironment();

		$this->flowContext = $environment->getContext('T3DD\Backend\Tests\Behat\FlowContext');
		$this->webApiContext = $environment->getContext('Behat\WebApiExtension\Context\WebApiContext');

		$this->objectManager = $this->flowContext->getObjectManager();
	}

	/**
	 * @Given I am a user :username with password :password and role :role
	 */
	public function iAmAUserWithPasswordAndRole($username, $password, $role) {
		$account = $this->objectManager->get('TYPO3\Flow\Security\AccountFactory')->createAccountWithPassword($username, $password, array('T3DD.Backend:' . $role), 'HttpBasic');
		$person = new \TYPO3\Party\Domain\Model\Person();
		$person->setName(new \TYPO3\Party\Domain\Model\PersonName('', '', '', '', ucfirst($username)));
		$this->objectManager->get('TYPO3\Party\Domain\Repository\PartyRepository')->add($person);
		$account->setParty($person);

		$this->objectManager->get('TYPO3\Flow\Security\AccountRepository')->add($account);
		$this->flowContext->persistAll();
		$this->webApiContext->iAmAuthenticatingAs($username, $password);
	}

	/**
	 * @Then /^I should see some output from behat$/
	 */
	public function iShouldSeeSomeOutputFromBehat() {
		return TRUE;
	}
}
