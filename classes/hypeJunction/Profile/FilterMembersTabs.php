<?php

namespace hypeJunction\Profile;

use Elgg\Collections\Collection;
use Elgg\HooksRegistrationService\Hook;

class FilterMembersTabs {

	public function __invoke(Hook $hook) {
		$remove = ['alpha', 'newest', 'popular', 'online'];

		$tabs = $hook->getValue();
		/* @var $tabs Collection */

		foreach ($remove as $name) {
			$tabs->remove($name);
		}

		$tabs->add(\ElggMenuItem::factory([
			'name' => 'all',
			'text' => elgg_echo('collection:user:user'),
			'href' => elgg_generate_url('collection:user:user'),
			'priority' => 100,
		]));

		return $tabs;
	}
}