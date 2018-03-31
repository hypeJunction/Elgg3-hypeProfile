<?php


namespace hypeJunction\Profile;

use Elgg\Hook;

class ConfigureRegistratinRoute {

	/**
	 * @elgg_plugin_hook route:config account:register
	 *
	 * @param Hook $hook Hook
	 *
	 * @return array
	 */
	public function __invoke(Hook $hook) {

		$conf = $hook->getValue();

		$midddleware = (array) elgg_extract('middleware', $conf, []);
		$midddleware[] = RegistrationMiddleware::class;

		$conf['middleware'] = $midddleware;

		return $conf;
	}
}