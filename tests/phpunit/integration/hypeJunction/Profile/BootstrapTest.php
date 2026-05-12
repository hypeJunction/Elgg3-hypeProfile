<?php

namespace hypeJunction\Profile;

use Elgg\IntegrationTestCase;

class BootstrapTest extends IntegrationTestCase {

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
    public function testPluginIsActive(): void {
		$plugin = elgg_get_plugin_from_id('hypeprofile');
		$this->assertInstanceOf(\ElggPlugin::class, $plugin);
		$this->assertTrue($plugin->isActive());
	}

	/**
     * @return void
     */
    public function testRouteConfigHookHandlerIsRegistered(): void {
		$this->assertTrue(_elgg_services()->hooks->hasHandler('route:config', 'action:register'));
	}

	/**
     * @return void
     */
    public function testFieldsUserHookHandlerIsRegistered(): void {
		$this->assertTrue(_elgg_services()->hooks->hasHandler('fields', 'user'));
	}

	/**
     * @return void
     */
    public function testUserCapabilityHookHandlersAreRegistered(): void {
		$hooks = _elgg_services()->hooks;
		$this->assertTrue($hooks->hasHandler('uses:icon', 'user:user'));
		$this->assertTrue($hooks->hasHandler('uses:cover', 'user:user'));
		$this->assertTrue($hooks->hasHandler('uses:comments', 'user:user'));
		$this->assertTrue($hooks->hasHandler('uses:river', 'user:user'));
		$this->assertTrue($hooks->hasHandler('uses:autosave', 'user:user'));
		$this->assertTrue($hooks->hasHandler('uses:location', 'user:user'));
	}

	/**
     * @return void
     */
    public function testWelcomeEmailEventHandlerIsRegistered(): void {
		$this->assertTrue(_elgg_services()->events->hasHandler('validate', 'user'));
	}

	/**
     * @return void
     */
    public function testInviteParamsHookHandlerIsRegistered(): void {
		$this->assertTrue(_elgg_services()->hooks->hasHandler('params', 'invite'));
	}

	/**
     * @return void
     */
    public function testMembersFilterMenuHookHandlerIsRegistered(): void {
		$this->assertTrue(_elgg_services()->hooks->hasHandler('register', 'menu:filter:members'));
	}

	/**
     * @return void
     */
    public function testRegisterActionIsRegistered(): void {
		$actions = _elgg_services()->actions->getAllActions();
		$this->assertArrayHasKey('register', $actions);
		$this->assertSame('public', $actions['register']['access']);
	}

	/**
     * @return void
     */
    public function testValidationActionsAreRegistered(): void {
		$actions = _elgg_services()->actions->getAllActions();
		$this->assertArrayHasKey('validation/is_valid_username', $actions);
		$this->assertArrayHasKey('validation/is_available_username', $actions);
		$this->assertArrayHasKey('validation/is_available_email', $actions);
	}

	/**
     * @return void
     */
    public function testProfileEditActionIsRegistered(): void {
		$actions = _elgg_services()->actions->getAllActions();
		$this->assertArrayHasKey('profile/edit', $actions);
	}

	/**
     * @return void
     */
    public function testRegisterRouteIsRegistered(): void {
		$route = _elgg_services()->routes->get('account:register');
		$this->assertNotNull($route);
		$this->assertStringStartsWith('/register/', $route->getPath());
	}

	/**
     * @return void
     */
    public function testPreregisterRouteIsRegistered(): void {
		$route = _elgg_services()->routes->get('account:preregister');
		$this->assertNotNull($route);
		$this->assertStringStartsWith('/preregister/form/', $route->getPath());
	}

	/**
     * @return void
     */
    public function testMembersRouteIsRegistered(): void {
		$route = _elgg_services()->routes->get('collection:user:user');
		$this->assertNotNull($route);
		$this->assertSame('/members', $route->getPath());
	}

	/**
     * @return void
     */
    public function testRegisterFormViewExists(): void {
		$this->assertTrue(elgg_view_exists('forms/register'));
	}

	/**
     * @return void
     */
    public function testSettingsViewExists(): void {
		$this->assertTrue(elgg_view_exists('plugins/hypeprofile/settings'));
	}
}
