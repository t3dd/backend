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
	 * @Given user :user has a participant with values:
	 */
	public function userHasAParticipantWithValues($user, TableNode $values) {
		$account = $this->objectManager->get('TYPO3\Flow\Security\AccountRepository')->findByAccountIdentifierAndAuthenticationProviderName($user, 'HttpBasic');
		if ($account === NULL) {
			throw new \Exception('User "' . $user . '" does not exist');
		}
		$propertyMappingConfiguration = new \TYPO3\Flow\Property\PropertyMappingConfiguration();
		$propertyMappingConfiguration->allowAllProperties();
		$propertyMappingConfiguration->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);
		$participant = $this->objectManager->get('TYPO3\Flow\Property\PropertyMapper')->convert($values->getRowsHash(), 'T3DD\Backend\Domain\Model\Participant', $propertyMappingConfiguration);
		$participant->setAccount($account);
		$this->objectManager->get('T3DD\Backend\Domain\Repository\ParticipantRepository')->add($participant);

		$this->flowContext->resolvePageUri('Participant api', array('participant' => $participant));
		$this->flowContext->persistAll();
	}

	/**
	 * @Then the there should be a persisted Participant for user :user with
	 */
	public function theThereShouldBeAPersistedParticipantForUserWith($user, TableNode $table) {
		$account = $this->objectManager->get('TYPO3\Flow\Security\AccountRepository')->findByAccountIdentifierAndAuthenticationProviderName($user, 'HttpBasic');
		if ($account === NULL) {
			throw new \Exception('User "' . $user . '" does not exist');
		}
		$participant = $this->objectManager->get('T3DD\Backend\Domain\Repository\ParticipantRepository')->findOneByAccount($account);
		if ($participant === NULL) {
			throw new \Exception('No participant found for user "' . $user . '"');
		}
		foreach ($table->getRowsHash() as $property => $value) {
			PHPUnit_Framework_Assert::assertEquals($value, (string) \TYPO3\Flow\Reflection\ObjectAccess::getProperty($participant, $property), 'Property "' . $property . '" of participant does not equal expected value');
		}
	}

	/**
	 * @Then there should be a persisted :class :identifier with values:
	 */
	public function theThereShouldBeAPersistedEntityWithValues($class, $identifier, TableNode $table) {
		$identityProperties = $this->objectManager->get('TYPO3\Flow\Reflection\ReflectionService')->getClassSchema('T3DD\Backend\Domain\Model\\' . $class)->getIdentityProperties();
		if (count($identityProperties) !== 1) {
			throw new \Exception('Finding a persisted entity only works for entities with exactly one identity property');
		}
		$identityProperty = array_keys($identityProperties)[0];
		$findMethodName = 'findOneBy' . ucfirst($identityProperty);

		$entity = $this->objectManager->get('T3DD\Backend\Domain\Repository\\' . $class . 'Repository')->$findMethodName($identifier);
		if ($entity === NULL) {
			throw new \Exception('No ' . $class . ' found with identity "' . $identifier . '"');
		}
		foreach ($table->getRowsHash() as $property => $value) {
			PHPUnit_Framework_Assert::assertEquals($value, (string) \TYPO3\Flow\Reflection\ObjectAccess::getProperty($entity, $property), 'Property "' . $property . '" of ' . $class . ' does not equal expected value');
		}
	}

	/**
	 * @Then there should be no persisted Participant
	 */
	public function thereShouldBeNoPersistedParticipant() {
		PHPUnit_Framework_Assert::assertEquals(0, $this->objectManager->get('T3DD\Backend\Domain\Repository\ParticipantRepository')->countAll(), 'There is a persisted participant');
	}

	/**
	 * @Given I have a :class with values
	 */
	public function iHaveAEntityWithValues($class, TableNode $values) {
		$propertyMappingConfiguration = new \TYPO3\Flow\Property\PropertyMappingConfiguration();
		$propertyMappingConfiguration->allowAllProperties();
		$propertyMappingConfiguration->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);
		$entity = $this->objectManager->get('TYPO3\Flow\Property\PropertyMapper')->convert($values->getRowsHash(), 'T3DD\Backend\Domain\Model\\' . $class, $propertyMappingConfiguration);
		$this->objectManager->get('T3DD\Backend\Domain\Repository\\' . $class . 'Repository')->add($entity);
		$this->flowContext->resolvePageUri('Single ' . $class, array(lcfirst($class) => $entity));
		$this->flowContext->persistAll();
	}



	/**
	 * @Then /^I should see some output from behat$/
	 */
	public function iShouldSeeSomeOutputFromBehat() {
		return TRUE;
	}
}
