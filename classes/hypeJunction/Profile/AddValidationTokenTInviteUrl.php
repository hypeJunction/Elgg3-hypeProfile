<?php

namespace hypeJunction\Profile;

use Elgg\Event;

class AddValidationTokenTInviteUrl {

	/**
	 * Add email validation token to outgoing invite emails
	 *
	 * @param Event $event Event
	 *
	 * @return mixed
	 */
	public function __invoke(Event $event) {

		$email = $event->getParam('email');

		// Email validation token
		$token = \elgg_build_hmac(['email' => $email])->getToken();
		$token = substr($token, 0, 10);

		$params = $event->getValue();
		$params['ev'] = $token;

		return $params;
	}
}