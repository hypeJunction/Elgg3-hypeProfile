<?php

namespace hypeJunction\Profile;

use Elgg\Request;

class IsAvailableUsername {

	/**
	 * Validate username availability
	 *
	 * @param Request $request Request
	 *
	 * @return \Elgg\Http\OkResponse
	 * @throws \Exception
	 */
	public function __invoke(Request $request) {

		elgg_signed_request_gatekeeper();

		$username = $request->getParam('username', '');
		$username = trim($username);

		$available = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function () use ($username) {
			return !get_user_by_username($username);
		});

		$data = json_encode([
			'username' => $username,
			'available' => $available,
			'error' => !$available ? elgg_echo('validation:error:type:availableusername') : null,
		]);

		return elgg_ok_response($data);
	}
}