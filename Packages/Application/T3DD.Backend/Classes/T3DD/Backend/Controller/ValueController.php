<?php
namespace T3DD\Backend\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use T3DD\Backend\Domain\Model\Value;
use T3DD\Backend\Domain\Model\ValueRepository;
use TYPO3\Flow\Annotations as Flow;

class ValueController extends \Netlogix\Crud\Controller\RestController {

	/**
	 * @Flow\Inject
	 * @var \T3DD\Backend\Domain\Repository\ValueRepository
	 */
	protected $valueRepository;

	/**
	 * @Flow\Inject
	 * @var \Netlogix\Crud\Domain\Service\DataTransferObjectFactory
	 */
	protected $dataTransferObjectFactory;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'value';

	/**
	 *
	 */
	public function listAction() {
		$values = array();

		/** @var Value $value */
		foreach ($this->valueRepository->findAll() as $value) {
			$values[$value->getType()][] = $this->dataTransferObjectFactory->getDataTransferObject($value);
		}

		$this->view->assign('value', $values);
	}

	/**
	 * @param Value $value
	 */
	public function showAction(Value $value) {
		$this->view->assign('value', $this->dataTransferObjectFactory->getDataTransferObject($value));
	}

	/**
	 * @param Value $value
	 */
	public function createAction(Value $value) {
		$this->valueRepository->add($value);
		$this->reportSuccess($value, 201);
	}

	/**
	 * @param Value $value
	 */
	public function updateAction(Value $value) {
		$this->valueRepository->update($value);
		$this->reportSuccess($value);
	}

	/**
	 * @param Value $value
	 */
	public function deleteAction(Value $value) {
		$this->valueRepository->remove($value);
	}

}
