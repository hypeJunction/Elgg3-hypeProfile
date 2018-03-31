<?php

namespace hypeJunction\Profile;

use Elgg\Event;

class SendWelcomeEmail {

	/**
	 * Send welcome email when account is validated
	 *
	 * @param Event $event Event
	 *
	 * @return void
	 * @throws \NotificationException
	 */
	public function __invoke(Event $event) {

		$user = $event->getObject();
		/* @var $user \ElggUser */

		if ($user->welcome_email_sent) {
			return;
		}

		$text = elgg_echo('register:welcome_email', [], $user->language);

		if (!$text) {
			$text = elgg_get_plugin_setting('welcome_email', 'hypeProfile');
		}

		if (!$text) {
			return;
		}

		$replacements = [
			'{{name}}' => $user->first_name ? : $user->name,
			'{{profile_url}}' => $user->getURL(),
			'{{site_name}}' => elgg_get_site_entity()->name,
			'{{site_url}}' => elgg_get_site_url(),
		];

		foreach ($replacements as $pattern => $replacement) {
			$text = str_replace($pattern, $replacement, $text);
		}

		$subject = elgg_echo('register:welcome_email:subject', [elgg_get_site_entity()->name]);

		notify_user($user->guid, 0, $subject, $text, [
			'action' => 'validate',
			'object' => $user,
			'summary' => $subject,
		], ['email']);

		$user->welcome_email_sent = time();

	}
}