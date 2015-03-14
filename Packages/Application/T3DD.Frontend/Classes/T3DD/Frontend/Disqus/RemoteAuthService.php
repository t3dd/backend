<?php
namespace T3DD\Frontend\Disqus;

/**
 * Enter descriptions here
 */
class RemoteAuthService {

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * Inject the settings
	 *
	 * @param array $settings
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 * @return string
	 */
	public function generateDisqusRemoteAuth(\TYPO3\Flow\Security\Account $account) {
		/** @var \TYPO3\Party\Domain\Model\Person $person */
		$person = $account->getParty();
		$data = array(
			'id' => $account->getAccountIdentifier(),
			'username' => $person->getName()->getFullName(),
			'email' => $person->getPrimaryElectronicAddress()->getIdentifier(),
			'avatar' => sprintf('http://typo3.org/services/userimage.php?username=%s&size=big', $account->getAccountIdentifier())
		);

		if (!isset($this->settings['disqusApiSecret'])) {
			throw new \InvalidArgumentException('You need to set up a disqusApiSecrect in your settings.', 1425572965);
		}

		$message = base64_encode(json_encode($data));
		$timestamp = time();
		$apiSecret = $this->settings['disqusApiSecret'];

		$hmac = $this->disqusHmacSha1($message . ' ' . $timestamp, $apiSecret);
		return $message . ' ' . $hmac . ' ' . $timestamp;
	}

	/**
	 * @param $data
	 * @param $key
	 * @return string
	 */
	protected function disqusHmacSha1($data, $key) {
		$blocksize = 64;
		$hashfunc = 'sha1';
		if (strlen($key) > $blocksize) {
			$key = pack('H*', $hashfunc($key));
		}
		$key = str_pad($key, $blocksize, chr(0x00));
		$ipad = str_repeat(chr(0x36), $blocksize);
		$opad = str_repeat(chr(0x5c), $blocksize);
		$hmac = pack(
			'H*',
			$hashfunc(
				($key ^ $opad) . pack(
					'H*',
					$hashfunc(
						($key ^ $ipad) . $data
					)
				)
			)
		);
		return bin2hex($hmac);
	}

}