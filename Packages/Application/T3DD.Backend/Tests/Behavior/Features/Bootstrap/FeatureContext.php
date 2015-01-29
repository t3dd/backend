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
	private $flowContext;

	/** @BeforeScenario */
	public function gatherContexts(BeforeScenarioScope $scope) {
		$environment = $scope->getEnvironment();

		$this->flowContext = $environment->getContext('T3DD\Backend\Tests\Behat\FlowContext');

		$this->objectManager = $this->flowContext->getObjectManager();
	}

	/**
	 * @Then /^I should see some output from behat$/
	 */
	public function iShouldSeeSomeOutputFromBehat() {
		return TRUE;
	}
}
