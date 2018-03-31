<?php

namespace hypeJunction\Profile;

use Elgg\Request;

class IsValidUsername {

	/**
	 * Validate username format
	 *
	 * @param Request $request Request
	 *
	 * @return \Elgg\Http\OkResponse
	 */
	public function __invoke(Request $request) {

		elgg_signed_request_gatekeeper();

		$username = $request->getParam('username', '');
		$username = trim($username);

		try {
			$valid = validate_username($username);
		} catch (\RegistrationException $e) {
			$valid = false;
		}

		$data = json_encode(array(
			'username' => $username,
			'valid' => $valid,
			'error' => !$valid ? elgg_echo('validation:error:type:validusername') : null,
		));

		return elgg_ok_response($data);
	}
}