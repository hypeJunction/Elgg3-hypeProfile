<?php

namespace hypeJunction\Profile;

use Elgg\Hook;

class AddValidationTokenTInviteUrl {

	/**
	 * Add email validation token to outgoing invite emails
	 *
	 * @param Hook $hook Hook
	 *
	 * @return mixed
	 */
	public function __invoke(Hook $hook) {

		$email = $hook->getParam('email');

		// Email validation token
		$token = elgg_build_hmac(['email' => $email])->getToken();
		$token = substr($token, 0, 10);

		$params = $hook->getValue();
		$params['ev'] = $token;

		return $params;
	}
}