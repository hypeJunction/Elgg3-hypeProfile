<?php

namespace hypeJunction\Profile;

use Elgg\PluginBootstrap;

class Bootstrap extends PluginBootstrap {

	/**
	 * Executed during 'plugin_boot:before', 'system' event
	 *
	 * Allows the plugin to require additional files, as well as configure services prior to booting the plugin
	 *
	 * @return void
	 */
	public function load() {

	}

	/**
	 * Executed during 'plugin_boot:before', 'system' event
	 *
	 * Allows the plugin to register handlers for 'plugin_boot', 'system' and 'init', 'system' events,
	 * as well as implement boot time logic
	 *
	 * @return void
	 */
	public function boot() {
		$this->elgg()->hooks->registerHandler('route:config', 'action:register', ConfigureRegistratinRoute::class);
	}

	/**
	 * Executed during 'init', 'system' event
	 *
	 * Allows the plugin to implement business logic and register all other handlers
	 *
	 * @return void
	 */
	public function init() {
		$this->elgg()->hooks->registerHandler('fields', 'user', \hypeJunction\Profile\SetUserFields::class);

		$this->elgg()->hooks->registerHandler('uses:icon', 'user:user', [\Elgg\Values::class, 'getTrue']);
		$this->elgg()->hooks->registerHandler('uses:cover', 'user:user', [\Elgg\Values::class, 'getTrue']);
		$this->elgg()->hooks->registerHandler('uses:comments', 'user:user', [\Elgg\Values::class, 'getFalse']);
		$this->elgg()->hooks->registerHandler('uses:river', 'user:user', [\Elgg\Values::class, 'getFalse']);
		$this->elgg()->hooks->registerHandler('uses:autosave', 'user:user', [\Elgg\Values::class, 'getFalse']);
		$this->elgg()->hooks->registerHandler('uses:location', 'user:user', [\Elgg\Values::class, 'getTrue']);

		$this->elgg()->events->registerHandler('validate', 'user', SendWelcomeEmail::class);

		$this->elgg()->hooks->registerHandler('params', 'invite', AddValidationTokenTInviteUrl::class);

		elgg_extend_view('input/text', 'forms/validation/username');
		elgg_extend_view('input/email', 'forms/validation/email');
		elgg_extend_view('input/password', 'forms/validation/password');

		elgg_extend_view('elgg.css', 'forms/register.css');
		elgg_extend_view('elgg.css', 'profile/extras.css');

		if (elgg_is_active_plugin('members')) {
			elgg_unregister_plugin_hook_handler('register', 'menu:filter:members', 'members_register_filter_menu');

			elgg_unregister_route('collection:user:user:alpha');
			elgg_unregister_route('collection:user:user:newest');
			elgg_unregister_route('collection:user:user:online');
			elgg_unregister_route('collection:user:user:popular');
			elgg_unregister_route('search:user:user');
		}

		elgg_register_collection("collection:user:user", DefaultMemberCollection::class);

		elgg_register_plugin_hook_handler('register', 'menu:filter:members', FilterMembersTabs::class, 800);
	}

	/**
	 * Executed during 'ready', 'system' event
	 *
	 * Allows the plugin to implement logic after all plugins are initialized
	 *
	 * @return void
	 */
	public function ready() {
		elgg_unextend_view('forms/usersettings/save', 'core/settings/account/name');
	}

	/**
	 * Executed during 'shutdown', 'system' event
	 *
	 * Allows the plugin to implement logic during shutdown
	 *
	 * @return void
	 */
	public function shutdown() {

	}

	/**
	 * Executed when plugin is activated, after 'activate', 'plugin' event and before activate.php is included
	 *
	 * @return void
	 */
	public function activate() {

	}

	/**
	 * Executed when plugin is deactivated, after 'deactivate', 'plugin' event and before deactivate.php is included
	 *
	 * @return void
	 */
	public function deactivate() {

	}

	/**
	 * Registered as handler for 'upgrade', 'system' event
	 *
	 * Allows the plugin to implement logic during system upgrade
	 *
	 * @return void
	 */
	public function upgrade() {

	}
}