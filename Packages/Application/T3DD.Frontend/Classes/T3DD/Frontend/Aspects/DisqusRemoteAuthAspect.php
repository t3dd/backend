<?php
namespace T3DD\Frontend\Aspects;


use TYPO3\Flow\Annotations as Flow;

/**
 * Enter descriptions here
 *
 * @Flow\Aspect
 */
class DisqusRemoteAuthAspect {

	const DISQUS_SECRET_KEY = 'yHAieA0TLdgWafMf3K16XXYnwRn2m7RTVVzAaLIGfvF6dvD8mnu1nQqz3MMdGbFF';

	/**
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint
	 * @Flow\Around("method(T3DD\Login\Controller\AuthenticationController->buildAccountDTO())")
	 */
	public function addDisqusRemoteAuthToUserDto(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		$userDto = $joinPoint->getAdviceChain()->proceed($joinPoint);
		$account = $joinPoint->getMethodArgument('account');
		$userDto->disqusRemoteAuth = $this->generateDisqusRemoteAuth($account);
		return $userDto;
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 * @return string
	 */
	protected function generateDisqusRemoteAuth(\TYPO3\Flow\Security\Account $account) {
		/** @var \TYPO3\Party\Domain\Model\Person $person */
		$person = $account->getParty();
		$data = array(
			'id' => $account->getAccountIdentifier(),
			'username' => $person->getName()->getFullName(),
			'email' => $person->getPrimaryElectronicAddress()->getIdentifier(),
			'avatar' => sprintf('http://typo3.org/services/userimage.php?username=%s&size=big', $account->getAccountIdentifier())
		);

		$message = base64_encode(json_encode($data));
		$timestamp = time();
		$hmac = $this->disqusHmacSha1($message . ' ' . $timestamp, static::DISQUS_SECRET_KEY);
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