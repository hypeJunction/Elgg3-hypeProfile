<?php

namespace hypeJunction\Profile;

use Elgg\Exceptions\Http\GatekeeperException;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;

class RegistrationGatekeeper {

	/**
	 * Registration form gatekeeper
	 *
	 * @param Request $request Request
	 *
	 * @return ResponseBuilder
	 * @throws GatekeeperException
	 */
	public function __invoke(Request $request) {

		if (elgg_is_logged_in()) {
			$user = elgg_get_logged_in_user_entity();
			$exception = new GatekeeperException();
			$exception->setRedirectUrl($user->getURL());
			throw $exception;
		}

		if (elgg_get_config('allow_registration') == false) {
			throw new GatekeeperException(elgg_echo('registerdisabled'));
		}

		if (elgg_get_plugin_setting('email_validation', 'hypeprofile')) {
			if (!elgg_http_validate_signed_url($request->getURL())) {
				$params = $request->getParams();
				unset($params['_route']);

				$url = elgg_generate_url('account:preregister', $params);

				return elgg_redirect_response($url);
			}
		}

		// unpack registration data
		$registration_url_params = $request->getParam('q');
		if (is_array($registration_url_params)) {
			foreach ($registration_url_params as $key => $val) {
				$request->setParam($key, $val);
			}
		}
	}
}