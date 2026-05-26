<?php


namespace hypeJunction\Profile;

use Elgg\Event;

class ConfigureRegistratinRoute {

	/**
	 * @elgg_event route:config account:register
	 *
	 * @param Event $event Event
	 *
	 * @return array
	 */
	public function __invoke(Event $event) {

		$conf = $event->getValue();

		$midddleware = (array) \elgg_extract('middleware', $conf, []);
		$midddleware[] = RegistrationMiddleware::class;

		$conf['middleware'] = $midddleware;

		return $conf;
	}
}