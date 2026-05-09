<?php

namespace hypeJunction\Profile;

use Elgg\IntegrationTestCase;

class BootstrapTest extends IntegrationTestCase {

	public function getPluginID(): string {
		return 'hypeprofile';
	}

	public function up(): void {
	}

	public function down(): void {
	}

	public function testPluginIsActive(): void {
		$plugin = elgg_get_plugin_from_id('hypeprofile');
		$this->assertInstanceOf(\ElggPlugin::class, $plugin);
		$this->assertTrue($plugin->isActive());
	}

	public function testRouteConfigHookHandlerIsRegistered(): void {
		$this->assertTrue(_elgg_services()->events->hasHandler('route:config', 'action:register'));
	}

	public function testFieldsUserHookHandlerIsRegistered(): void {
		$this->assertTrue(_elgg_services()->events->hasHandler('fields', 'user'));
	}

	public function testUserCapabilityHookHandlersAreRegistered(): void {
		$events = _elgg_services()->events;
		$this->assertTrue($events->hasHandler('uses:icon', 'user:user'));
		$this->assertTrue($events->hasHandler('uses:cover', 'user:user'));
		$this->assertTrue($events->hasHandler('uses:comments', 'user:user'));
		$this->assertTrue($events->hasHandler('uses:river', 'user:user'));
		$this->assertTrue($events->hasHandler('uses:autosave', 'user:user'));
		$this->assertTrue($events->hasHandler('uses:location', 'user:user'));
	}

	public function testWelcomeEmailEventHandlerIsRegistered(): void {
		$this->assertTrue(_elgg_services()->events->hasHandler('validate', 'user'));
	}

	public function testInviteParamsHookHandlerIsRegistered(): void {
		$this->assertTrue(_elgg_services()->events->hasHandler('params', 'invite'));
	}

	public function testMembersFilterMenuHookHandlerIsRegistered(): void {
		$this->assertTrue(_elgg_services()->events->hasHandler('register', 'menu:filter:members'));
	}

	public function testRegisterActionIsRegistered(): void {
		$actions = _elgg_services()->actions->getAllActions();
		$this->assertArrayHasKey('register', $actions);
		$this->assertSame('public', $actions['register']['access']);
	}

	public function testValidationActionsAreRegistered(): void {
		$actions = _elgg_services()->actions->getAllActions();
		$this->assertArrayHasKey('validation/is_valid_username', $actions);
		$this->assertArrayHasKey('validation/is_available_username', $actions);
		$this->assertArrayHasKey('validation/is_available_email', $actions);
	}

	public function testProfileEditActionIsRegistered(): void {
		$actions = _elgg_services()->actions->getAllActions();
		$this->assertArrayHasKey('profile/edit', $actions);
	}

	public function testRegisterRouteIsRegistered(): void {
		$route = _elgg_services()->routes->get('account:register');
		$this->assertNotNull($route);
		$this->assertStringStartsWith('/register/', $route->getPath());
	}

	public function testPreregisterRouteIsRegistered(): void {
		$route = _elgg_services()->routes->get('account:preregister');
		$this->assertNotNull($route);
		$this->assertStringStartsWith('/preregister/form/', $route->getPath());
	}

	public function testMembersRouteIsRegistered(): void {
		$route = _elgg_services()->routes->get('collection:user:user');
		$this->assertNotNull($route);
		$this->assertSame('/members', $route->getPath());
	}

	public function testRegisterFormViewExists(): void {
		$this->assertTrue(elgg_view_exists('forms/register'));
	}

	public function testSettingsViewExists(): void {
		$this->assertTrue(elgg_view_exists('plugins/hypeprofile/settings'));
	}
}
