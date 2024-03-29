<?php

namespace hypeJunction\Profile;

use Elgg\Hook;
use ElggUser;
use hypeJunction\Fields\Collection;
use hypeJunction\Fields\ControlElement;
use hypeJunction\Fields\CoverField;
use hypeJunction\Fields\CustomHtml;
use hypeJunction\Fields\HiddenField;
use hypeJunction\Fields\IconField;
use hypeJunction\Fields\MetaField;

class SetUserFields {

	/**
	 * Setup group fields
	 *
	 * @param Hook $hook Hook
	 *
	 * @return array|null
	 * @throws \InvalidParameterException
	 */
	public function __invoke(Hook $hook) {

		$entity = $hook->getEntityParam();

		if (!$entity instanceof ElggUser) {
			return null;
		}

		$fields = $hook->getValue();
		/* @var $fields Collection */

		if (elgg_get_plugin_setting('first_last_name', 'hypeProfile')) {
			$fields->add('name', new FullNameField([
				'type' => 'profile/full_name',
				'#label' => false,
				'name' => 'name',
				'required' => true,
				'priority' => 100,
				'is_profile_field' => false,
				'is_edit_field' => true,
				'is_create_field' => true,
				'is_export_field' => true,
				'data-parsley-trigger' => 'input change',
				'data-parsley-debounce' => 1000,
				'value' => function () {
					$fn = get_input('fn');
					$ln = get_input('ln');

					if ($fn || $ln) {
						return [
							'first_name' => $fn,
							'last_name' => $ln,
						];
					}
				},
				'#help' => function () {
					$help = '';
					if (elgg_get_plugin_setting('last_name_abbr', 'hypeProfile')) {
						$help = elgg_echo('profile:last_name_abbr:help');
					}

					return $help;
				}
			]));
		} else if (elgg_get_plugin_setting('autogen_name', 'hypeProfile', false)) {
			// do not display anything if name is autogenerated
		} else {
			$fields->add('name', new MetaField([
				'type' => 'text',
				'priority' => 100,
				'is_profile_field' => false,
				'is_edit_field' => true,
				'is_create_field' => true,
				'is_export_field' => true,
				'required' => true,
				'maxlength' => 50,
				'data-parsley-trigger' => 'input change',
				'data-parsley-debounce' => 1000,
				'value' => get_input('n'),
			]));
		}

		$email_account_validator = elgg_generate_action_url('validation/is_available_email');
		$email_account_validator = elgg_http_get_signed_url($email_account_validator);

		$email_value = get_input('e');
		$fields->add('email', new MetaField([
			'type' => $email_value ? 'hidden' : 'email',
			'priority' => 101,
			'required' => true,
			'is_profile_field' => false,
			'is_create_field' => true,
			'is_edit_field' => false,
			'is_export_field' => true,
			'data-parsley-emailaccount' => $email_account_validator,
			'data-parsley-trigger' => 'input change',
			'data-parsley-debounce' => 1000,
			'value' => $email_value,
		]));

		if (elgg_get_plugin_setting('autogen_username', 'hypeProfile', false)) {
			// Display nothing
		} else {
			$username_account_validator = elgg_generate_action_url('validation/is_available_username');
			$username_account_validator = elgg_http_get_signed_url($username_account_validator);

			$username_validator = elgg_generate_action_url('validation/is_valid_username');
			$username_validator = elgg_http_get_signed_url($username_validator);

			$fields->add('username', new UsernameField([
				'type' => 'text',
				'priority' => 102,
				'name' => 'username',
				'required' => true,
				'is_profile_field' => false,
				'is_create_field' => true,
				'is_edit_field' => false,
				'is_export_field' => true,
				'minlength' => elgg_get_config('minusername') ? : 4,
				'data-parsley-validusername' => $username_validator,
				'data-parsley-availableusername' => $username_account_validator,
				'data-parsley-trigger' => 'input change',
				'data-parsley-debounce' => 1000,
			]));
		}

		if (elgg_get_plugin_setting('autogen_password', 'hypeProfile', false)) {
			// Do not display anything
		} else {
			$fields->add('password', new MetaField([
				'type' => 'password',
				'priority' => 103,
				'#id' => 'password',
				'required' => true,
				'is_profile_field' => false,
				'is_create_field' => true,
				'is_edit_field' => false,
				'minlength' => elgg_get_config('min_password_length') ? : 6,
				'data-parsley-minstrength' => elgg_get_plugin_setting('min_password_strength', 'hypeProfile', 0),
				'data-parsley-trigger' => 'input change',
				'data-parsley-debounce' => 1000,
			]));

			if (!elgg_get_plugin_setting('hide_password_repeat', 'hypeProfile')) {
				$fields->add('password2', new MetaField([
					'type' => 'password',
					'priority' => 104,
					'name' => 'password2',
					'required' => true,
					'is_profile_field' => false,
					'is_create_field' => true,
					'is_edit_field' => false,
					'data-parsley-equalto' => '#password',
					'data-parsley-trigger' => 'input change',
					'data-parsley-debounce' => 1000,
				]));
			}
		}

		$config = (array) elgg_get_config('profile_fields');

		foreach ($config as $prop => $type) {
			$fields->add($prop, new ProfileField([
				'type' => $type,
				'is_profile_field' => true,
				'is_edit_field' => true,
				'is_create_field' => false,
				'priority' => 500,
				'is_export_field' => true,
			]));
		}

		$fields->add('icon', new IconField([
			'type' => 'file',
			'is_profile_field' => false,
			'is_edit_field' => true,
			'is_create_field' => (bool) elgg_get_plugin_setting('icon_input', 'hypeProfile'),
			'priority' => 400,
			'section' => $entity->guid ? 'sidebar' : 'content',
		]));

		$fields->add('cover', new CoverField([
			'type' => 'file',
			'section' => 'sidebar',
			'is_profile_field' => false,
			'is_edit_field' => true,
			'is_create_field' => false,
			'is_export_field' => true,
			'priority' => 400,
		]));

		$vars = $hook->getParams();

		$fields->add('_extend', new CustomHtml([
			'#html' => function () use ($vars) {
				return elgg_view('register/extend', $vars);
			},
			'is_profile_field' => false,
			'is_edit_field' => false,
			'is_create_field' => true,
			'priority' => 800,
		]));

		$fields->add('friend_guid', new HiddenField([
			'type' => 'hidden',
			'value' => $hook->getParam('friend_guid'),
			'is_profile_field' => false,
			'is_edit_field' => false,
			'is_create_field' => true,
		]));

		$fields->add('invitecode', new HiddenField([
			'type' => 'hidden',
			'value' => $hook->getParam('invitecode'),
			'is_profile_field' => false,
			'is_edit_field' => false,
			'is_create_field' => true,
		]));

		$fields->add('_captcha', new CustomHtml([
			'#html' => function () use ($vars) {
				return elgg_view('input/captcha', $vars);
			},
			'is_profile_field' => false,
			'is_edit_field' => false,
			'is_create_field' => true,
			'priority' => 810,
		]));

		if ($terms_url = elgg_get_plugin_setting('terms_url', 'hypeProfile')) {
			$link = elgg_view('output/url', [
				'href' => $terms_url,
				'text' => elgg_echo('register:terms'),
				'target' => '_blank',
			]);

			$label = elgg_echo('register:terms:agree', [$link]);

			$fields->add('agree_to_terms', new MetaField([
				'type' => 'checkbox',
				'#label' => false,
				'label' => $label,
				'label_tag' => 'label',
				'is_profile_field' => false,
				'is_edit_field' => false,
				'is_create_field' => true,
				'is_export_field' => true,
				'priority' => 980,
				'required' => true,
				'value' => function() {
					return time();
				},
			]));
		}

		if ($privacy_url = elgg_get_plugin_setting('privacy_url', 'hypeProfile')) {
			$link = elgg_view('output/url', [
				'href' => $privacy_url,
				'text' => elgg_echo('register:privacy'),
				'target' => '_blank',
			]);

			$label = elgg_echo('register:privacy:agree', [$link]);

			$fields->add('agree_to_privacy', new MetaField([
				'type' => 'checkbox',
				'#label' => false,
				'label' => $label,
				'label_tag' => 'label',
				'is_profile_field' => false,
				'is_edit_field' => false,
				'is_create_field' => true,
				'is_export_field' => true,
				'priority' => 990,
				'required' => true,
				'value' => function() {
					return time();
				},
			]));
		}

		$fields->add('submit', new ControlElement([
			'type' => 'submit',
			'section' => 'actions',
			'value' => function (\ElggEntity $entity) {
				return $entity->guid ? elgg_echo('save') : elgg_echo('register');
			},
			'priority' => 600,
			'contexts' => false,
			'is_profile_field' => false,
			'is_edit_field' => true,
			'is_create_field' => true,
		]));

		$field_access_conf = elgg_get_plugin_setting('field_access', 'hypeProfile');
		if ($field_access_conf == ProfileField::GLOBAL_PICKER) {
			$fields->add('profile_field_access', new MetaField([
				'type' => 'access',
				'is_profile_field' => false,
				'is_edit_field' => true,
				'is_create_field' => false,
				'priority' => 1,
				'required' => true,
			]));
		}

		return $fields;
	}
}