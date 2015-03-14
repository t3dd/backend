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
	 * @Flow\Inject
	 * @var \T3DD\Frontend\Disqus\RemoteAuthService
	 */
	protected $disqusRemoteAuthService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

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
		// IE Needs this otherwise it reuses the html response as cached result for the json response
		$headers = $this->response->getHeaders();
		$headers->setCacheControlDirective('no-cache');
		$headers->setCacheControlDirective('must-revalidate');
		$html = file_get_contents('resource://T3DD.Frontend/Public/' . $this->targetDirectory . '/index.html');
		return $this->rewriteResourceUrls($html);
	}

	/**
	 * @param string $title
	 * @param string $uri
	 */
	public function disqusAction($title, $uri) {
		$this->view->assign('title', $title);
		$this->view->assign('url', $uri);
		if ($account = $this->securityContext->getAccount()) {
			$this->view->assign('remoteAuth', $this->disqusRemoteAuthService->generateDisqusRemoteAuth($account));
		}
	}

	/**
	 * @param $html
	 * @return mixed
	 */
	protected function rewriteResourceUrls($html) {
		return preg_replace_callback('!(?:<(?:link|script|img|app-route|template)\s.*?>)+!i', function($matches) {
			return preg_replace('!(href|import|src|assetrootpath)="([^/\{].*?)"!i', '${1}="/_Resources/Static/Packages/T3DD.Frontend/' . $this->targetDirectory . '/${2}"', $matches[0]);
		}, $html);
	}

}