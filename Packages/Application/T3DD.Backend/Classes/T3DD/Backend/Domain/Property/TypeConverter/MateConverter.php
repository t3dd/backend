<?php
namespace T3DD\Backend\Domain\Property\TypeConverter;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class MateConverter extends \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter {

	/**
	 * @var \TYPO3\Flow\Property\PropertyMapper
	 * @Flow\Inject
	 */
	protected $propertyMapper;

	protected $sourceTypes = array('string');

	protected $targetType = 'T3DD\\Backend\\Domain\\Model\\Mate';

	protected $priority = 5;

	/**
	 * @param mixed $source
	 * @param string $targetType
	 * @param array $convertedChildProperties
	 * @param \TYPO3\Flow\Property\PropertyMappingConfigurationInterface $configuration
	 * @return object the target type
	 */
	public function convertFrom($source, $targetType, array $convertedChildProperties = array(), \TYPO3\Flow\Property\PropertyMappingConfigurationInterface $configuration = NULL) {
		if ($source === '') {
			return NULL;
		}

		// TODO: Check if we find a typo3.org user that match
		if (filter_var($source, FILTER_VALIDATE_EMAIL)) {
			$source = array('email' => $source);
		} else if (!strpos(' ', $source)) {
			$source = array('username' => $source);
		} else {
			$source = array('name' => $source);
		}

		/** @var \TYPO3\Flow\Mvc\Controller\MvcPropertyMappingConfiguration $configuration */
		$configuration->allowAllProperties();
		$configuration->setTypeConverterOption('TYPO3\\Flow\\Property\\TypeConverter\\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);
		return $this->propertyMapper->convert($source, $targetType, $configuration);
	}

	/**
	 * @param mixed $source
	 * @param string $targetType
	 * @return bool
	 */
	public function canConvertFrom($source, $targetType) {
		return true;
	}

}