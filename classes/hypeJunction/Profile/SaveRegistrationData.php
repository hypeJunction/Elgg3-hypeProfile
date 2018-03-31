<?php

namespace hypeJunction\Profile;

use Elgg\Hook;

class SaveRegistrationData {

	/**
	 * Save data provided on registration
	 *
	 * @param Hook $hook Hook
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function __invoke(Hook $hook) {

		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function () use ($hook) {
			$user = $hook->getUserParam();

			$parameters = elgg_get_config('action_register_bag');
			/* @var $parameters \Symfony\Component\HttpFoundation\ParameterBag */


		});
	}
}