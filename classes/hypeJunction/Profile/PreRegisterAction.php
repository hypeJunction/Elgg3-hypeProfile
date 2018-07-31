<?php

namespace hypeJunction\Profile;

use Elgg\Email;
use Elgg\Http\ResponseBuilder;
use Elgg\HttpException;
use Elgg\Request;

class PreRegisterAction {

	/**
	 * Send an email validation link
	 *
	 * @param Request $request Request
	 *
	 * @return ResponseBuilder
	 * @throws HttpException
	 */
	public function __invoke(Request $request) {

		$address = $request->getParam('email');

		try {
			elgg()->accounts->assertValidEmail($address);
		} catch (\Exception $ex) {
			throw new HttpException($ex->getMessage(), ELGG_HTTP_BAD_REQUEST);
		}

		$registration_url = elgg_generate_url('account:register', [
			'subtype' => $request->getParam('subtype'),
		]);

		$registration_url_params = (array) $request->getParam('q', []);

		$registration_url_params['e'] = $request->getParam('email');
		$registration_url_params['friend_guid'] = $request->getParam('friend_guid');
		$registration_url_params['invitecode'] = $request->getParam('invitecode');

		$registration_url = elgg_http_add_url_query_elements($registration_url, array_filter($registration_url_params));
		$registration_url = elgg_http_get_signed_url($registration_url);

		$subject = elgg_echo('preregister:email:subject');
		$body = elgg_echo('preregister:email:message', [
			elgg_get_site_entity()->getDisplayName(),
			$registration_url,
		]);

		$email = Email::factory([
			'to' => $address,
			'from' => '',
			'subject' => $subject,
			'body' => $body,
		]);

		elgg_send_email($email);

		$forward_url = elgg_generate_url('account:preregister:confirm', [
			'email' => $address,
		]);

		return elgg_ok_response([
			'forward' => $forward_url,
		], '', $forward_url);
	}
}