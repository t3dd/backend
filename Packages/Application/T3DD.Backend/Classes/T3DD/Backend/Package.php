<?php
namespace T3DD\Backend;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "T3DD.Backend".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Package\Package as BasePackage;
use TYPO3\Flow\Core\Bootstrap as Bootstrap;

class Package extends BasePackage {

	/**
	 * @param Bootstrap $bootstrap The current bootstrap
	 * @return void
	 */
	public function boot(Bootstrap $bootstrap) {
		$dispatcher = $bootstrap->getSignalSlotDispatcher();
		$dispatcher->connect('TYPO3\Flow\Core\Booting\Sequence', 'afterInvokeStep', function($step) use($bootstrap) {
			if ($step instanceof \TYPO3\Flow\Core\Booting\Step && $step->getIdentifier() == 'typo3.flow:persistence') {
				/** @var \Doctrine\Common\Persistence\ObjectManager $entityManager */
				$entityManager = $bootstrap->getObjectManager()->get(\Doctrine\Common\Persistence\ObjectManager::class);
				$entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
			}
		});
	}

}