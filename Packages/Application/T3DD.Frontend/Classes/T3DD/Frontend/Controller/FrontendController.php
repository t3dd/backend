<?php
namespace T3DD\Frontend\Controller;

/**
 * Enter descriptions here
 */
class FrontendController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 */
	public function serveAction() {
		$html = file_get_contents('resource://T3DD.Frontend/Public/app/index.html');
		return $this->rewriteResourceUrls($html);
	}

	/**
	 * @param $html
	 * @return mixed
	 */
	protected function rewriteResourceUrls($html) {
		return preg_replace_callback('!(?:<(?:link|script|img|app-route)\s.*?>)+!i', function($matches) {
			return preg_replace('!(href|import|src)="([^/\{].*?)"!i', '${1}="/_Resources/Static/Packages/T3DD.Frontend/app/${2}"', $matches[0]);
		}, $html);
	}

}