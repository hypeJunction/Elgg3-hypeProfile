<?php

namespace hypeJunction\Profile;

use Elgg\IntegrationTestCase;

class PluginSettingsLowercaseTest extends IntegrationTestCase {

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
	 * Regression: Elgg 4.x ElggPlugin::getSetting() uses lowercase plugin IDs only.
	 * Calls with the legacy camelCase ID `hypeProfile` silently return false (NOT
	 * the configured default), which was breaking every behaviour that branched
	 * on a plugin setting.
	 */
	public function testLowercasePluginIdResolvesSettings(): void {
		$plugin = elgg_get_plugin_from_id('hypeprofile');
		$plugin->setSetting('integration_test_marker', 'lowercase_works');

		try {
			$this->assertSame(
				'lowercase_works',
				elgg_get_plugin_setting('integration_test_marker', 'hypeprofile')
			);
		} finally {
			$plugin->unsetSetting('integration_test_marker');
		}
	}

	/**
     * @return void
     */
    public function testCamelCasePluginIdDoesNotResolve(): void {
		// elgg_get_plugin_from_id() returns null (not false) for unknown ids in 4.x,
		// but the contract is the same: camelCase must not resolve to the lowercase
		// plugin. This is the regression that silently broke every plugin-setting
		// branch (e.g. "first_last_name") prior to the lowercase fix.
		$camel = elgg_get_plugin_from_id('hypeProfile');
		$lower = elgg_get_plugin_from_id('hypeprofile');

		$this->assertInstanceOf(\ElggPlugin::class, $lower);
		$this->assertNotEquals($lower, $camel);
		$this->assertTrue($camel === null || $camel === false);
	}

	/**
     * @return void
     */
    public function testActivationDefaultSettingsArePresent(): void {
		$this->assertTrue((bool) elgg_get_plugin_setting('email_validation', 'hypeprofile'));
		$this->assertTrue((bool) elgg_get_plugin_setting('first_last_name', 'hypeprofile'));
		$this->assertTrue((bool) elgg_get_plugin_setting('hide_password_repeat', 'hypeprofile'));
		$this->assertSame(
			ProfileField::FIELD_PICKER,
			elgg_get_plugin_setting('field_access', 'hypeprofile')
		);
	}
}
