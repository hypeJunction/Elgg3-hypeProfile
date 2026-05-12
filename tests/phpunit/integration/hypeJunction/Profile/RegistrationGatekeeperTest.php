<?php

namespace hypeJunction\Profile;

use Elgg\Exceptions\Http\GatekeeperException;
use Elgg\IntegrationTestCase;
use Elgg\Request;

class RegistrationGatekeeperTest extends IntegrationTestCase {

	/**
     * @return string
     */
    public function getPluginID(): string {
		return 'hypeprofile';
	}

	/**
     * @return void
     */
    public function up(): void {
	}

	/**
     * @return void
     */
    public function down(): void {
	}

	/**
     * @return void
     */
    public function testThrowsWhenUserAlreadyLoggedIn(): void {
		$user = $this->createUser();
		elgg_get_session()->setLoggedInUser($user);

		try {
			$gatekeeper = new RegistrationGatekeeper();
			$request = $this->getMockBuilder(Request::class)
				->disableOriginalConstructor()
				->getMock();

			$this->expectException(GatekeeperException::class);
			$gatekeeper($request);
		} finally {
			elgg_get_session()->removeLoggedInUser();
		}
	}

	/**
     * @return void
     */
    public function testThrowsWhenRegistrationIsDisabled(): void {
		$previous = elgg_get_config('allow_registration');
		elgg_set_config('allow_registration', false);

		try {
			$gatekeeper = new RegistrationGatekeeper();
			$request = $this->getMockBuilder(Request::class)
				->disableOriginalConstructor()
				->getMock();

			$this->expectException(GatekeeperException::class);
			$this->expectExceptionMessage(elgg_echo('registerdisabled'));
			$gatekeeper($request);
		} finally {
			elgg_set_config('allow_registration', $previous);
		}
	}

	/**
     * @return void
     */
    public function testUnpacksQueryParametersBackOntoRequest(): void {
		$plugin = elgg_get_plugin_from_id('hypeprofile');
		$emailValidationPrev = $plugin->getSetting('email_validation');
		$plugin->setSetting('email_validation', 0);

		$previous = elgg_get_config('allow_registration');
		elgg_set_config('allow_registration', true);

		try {
			$received = [];
			$request = $this->getMockBuilder(Request::class)
				->disableOriginalConstructor()
				->getMock();
			$request->method('getParam')->willReturnCallback(function ($key) {
				return $key === 'q' ? ['friend_guid' => '42', 'invitecode' => 'abcd'] : null;
			});
			$request->method('setParam')->willReturnCallback(function ($k, $v) use (&$received) {
				$received[$k] = $v;
				return null;
			});

			$gatekeeper = new RegistrationGatekeeper();
			$gatekeeper($request);

			$this->assertSame('42', $received['friend_guid'] ?? null);
			$this->assertSame('abcd', $received['invitecode'] ?? null);
		} finally {
			elgg_set_config('allow_registration', $previous);
			$plugin->setSetting('email_validation', $emailValidationPrev);
		}
	}
}
