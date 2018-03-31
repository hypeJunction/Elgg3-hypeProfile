<?php

namespace hypeJunction\Profile;

use Elgg\Email;
use Elgg\Http\OkResponse;
use Elgg\Request;

class SendVerificationCodeAction {

	/**
	 * Trigger email validation and display a form
	 *
	 * @param Request $request Request
	 *
	 * @return OkResponse
	 */
	public function __invoke(Request $request) {

		$address = $request->getParam('email');
		$name = $request->getParam('name');

		if ($this->sendCode($address, $name)) {
			return elgg_ok_response('', elgg_echo('profile:validation:sent'));
		}

		return elgg_error_response(elgg_echo('profile:validation:not_sent'));
	}

	/**
	 * Send the email verification code
	 *
	 * @param string $address Email address
	 * @param string $name Name
	 *
	 * @return bool
	 */
	public function sendCode($address, $name) {
		$greeting = $name;
		if (is_array($name)) {
			$greeting = elgg_extract('first_name', $name, $greeting, false);
			$name = implode(' ', $name);
		}

		$token = elgg_build_hmac([
			'email' => $address,
		])->getToken();

		$token = substr($token, 0, 10);

		$email = new Email();

		$email->setTo(new Email\Address($address, $name));
		$email->setFrom(new Email\Address(elgg_get_site_entity()->getEmailAddress()));

		$email->setSubject(elgg_echo('profile:validation:email:subject', [
			elgg_get_site_entity()->name,
		]));

		$email->setBody(elgg_echo('profile:validation:email:message', [
			$greeting,
			$token,
		]));

		return elgg_send_email($email);
	}
}