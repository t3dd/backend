<?php
namespace T3DD\Frontend\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 T3DD Frontend.                        *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Enter descriptions here
 */
class FrontendController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Utility\Environment
	 */
	protected $environment;

	/**
	 * @var string
	 */
	protected $targetDirectory = 'dist';

	public function initializeServeAction() {
		if ($this->environment->getContext()->isDevelopment()) {
			$this->targetDirectory = 'app';
		}
	}

	/**
	 */
	public function serveAction() {

		$html = file_get_contents('resource://T3DD.Frontend/Public/' . $this->targetDirectory . '/index.html');
		return $this->rewriteResourceUrls($html);
	}

	/**
	 * @param $html
	 * @return mixed
	 */
	protected function rewriteResourceUrls($html) {
		return preg_replace_callback('!(?:<(?:link|script|img|app-route)\s.*?>)+!i', function($matches) {
			return preg_replace('!(href|import|src)="([^/\{].*?)"!i', '${1}="/_Resources/Static/Packages/T3DD.Frontend/' . $this->targetDirectory . '/${2}"', $matches[0]);
		}, $html);
	}

}