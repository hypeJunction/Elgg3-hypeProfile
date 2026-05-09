<?php

namespace hypeJunction\Profile;

use Elgg\Collections\Collection;
use Elgg\Event;

class FilterMembersTabs {

	public function __invoke(Event $event) {
		$remove = ['alpha', 'newest', 'popular', 'online'];

		$tabs = $event->getValue();
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