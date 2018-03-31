<?php

namespace hypeJunction\Profile;

use Elgg\Request;

class IsAvailableEmail {

	/**
	 * Validate email acount availability
	 *
	 * @param Request $request Request
	 *
	 * @return \Elgg\Http\OkResponse
	 * @throws \Exception
	 */
	public function __invoke(Request $request) {

		elgg_signed_request_gatekeeper();

		$email = $request->getParam('email', '');
		$email = trim($email);

		$available = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function () use ($email) {
			return !get_user_by_email($email);
		});

		$data = json_encode([
			'email' => $email,
			'available' => $available,
			'error' => !$available ? elgg_echo('validation:error:type:emailaccount') : null,
		]);

		return elgg_ok_response($data);
	}
}