<?php

namespace hypeJunction\Profile;

use Elgg\BadRequestException;
use Elgg\Event;
use Elgg\HttpException;
use Elgg\Request;
use Exception;
use hypeJunction\Fields\Field;
use hypeJunction\Fields\FieldInterface;
use hypeJunction\Post\Model;
use hypeJunction\ValidationException;
use Symfony\Component\HttpFoundation\ParameterBag;

class RegistrationMiddleware {

	/**
	 * Prepare registration data for the register action
	 *
	 * @param Request $request Request
	 *
	 * @return void
	 * @throws BadRequestException
	 * @throws Exception
	 */
	public function __invoke(Request $request) {

		elgg_make_sticky_form('register');

		$entity = new \ElggUser();

		$svc = elgg()->{'posts.model'};
		/* @var $svc Model */

		$fields = $svc->getFields($entity, Field::CONTEXT_CREATE_FORM);

		$fields = $fields->filter(function (FieldInterface $field) {
			if (in_array($field->name, \ElggEntity::$primary_attr_names)) {
				return false;
			}

			return true;
		});

		$parameters = new ParameterBag();

		$errors = [];

		foreach ($fields as $field) {
			/* @var $field \hypeJunction\Fields\FieldInterface */

			$value = $field->raw($request, $entity);

			if (!isset($value)) {
				// Field is not present
				continue;
			}

			$label = $field->label($entity);
			try {
				$field->validate($value);
			} catch (ValidationException $ex) {
				$errors[$field->name] = elgg_echo('validation:error', [$label, $ex->getMessage()]);
				continue;
			}

			$field->value = $value;
			$parameters->set($field->name, $value);
		}

		if ($errors) {
			throw new HttpException(implode("\r\n", $errors));
		}
		
		$name = $parameters->get('name');
		if (is_array($name)) {
			$first_name = $name['first_name'];
			$last_name = $name['last_name'];
		} else {
			$first_name = '';
			$last_name = '';
		}

		$email = $parameters->get('email', '');

		$username = $parameters->get('username');

		$password = $parameters->get('password', false);

		list($email_username) = explode('@', $email);

		$first_name = ucfirst($first_name);
		$last_name = ucfirst($last_name);

		if (elgg_get_plugin_setting('last_name_abbr', 'hypeProfile')) {
			$last_name = substr($last_name, 0, 1) . '.';
		}

		if (elgg_get_plugin_setting('first_last_name', 'hypeProfile')) {
			if (!$first_name || !$last_name) {
				throw new BadRequestException(elgg_echo('actions:register:error:first_last_name'));
			}

			$request->setParam('name', "$first_name $last_name");
		} else if (elgg_get_plugin_setting('autogen_name', 'hypeProfile')) {
			$request->setParam('name', $email_username);
		}

		if (elgg_get_plugin_setting('autogen_username', 'hypeProfile') && !$username) {
			$algo = elgg_get_plugin_setting('autogen_username_algo', 'hypeProfile', 'first_name_only');
			switch ($algo) {
				case 'first_name_only' :
					$username = $first_name ? : $email_username;
					break;
				case 'full_name' :
					$username = $first_name && $last_name ? "$first_name.$last_name" : $email_username;
					break;
				case 'email' :
					$username = $email_username;
					break;

				case 'alnum' :
					$username = '';
					break;
			}

			$username = $this->generateUsername($username);
			$request->setParam('username', $username);
		}

		if (elgg_get_plugin_setting('autogen_password', 'hypeProfile')) {
			$password = generate_random_cleartext_password();

			$request->setParam('password', $password);
			$request->setParam('password2', $password);
		} else {
			if ($min_strength = elgg_get_plugin_setting('min_password_strength', 'hypeProfile')) {
				$zxcvbn = new \ZxcvbnPhp\Zxcvbn();
				$strength = $zxcvbn->passwordStrength($password);
				if ($strength < $min_strength) {
					throw new BadRequestException(elgg_echo('actions:register:error:password_strength'));
				}
			}

			if (elgg_get_plugin_setting('hide_password_repeat', 'hypeProfile')) {
				$request->setParam('password2', $password);
			}
		}

		elgg_register_event_handler('create', 'user', function(Event $event) use ($fields, $parameters) {

			$entity = $event->getObject();
			/* @var $entity \ElggUser */

			$fields = $fields->filter(function(FieldInterface $field) {
				$clear = array_merge([
					'email',
					'username',
					'password',
					'password2',
					'_hash',
				], \ElggEntity::$primary_attr_names);

				return !in_array($field->name, $clear);
			});

			foreach ($fields as $field) {
				$field->save($entity, $parameters);
			}

		}, 1);
	}

	/**
	 * Generates a unique available and valid username
	 *
	 * @param string $username Username prefix
	 *
	 * @return string
	 * @throws Exception
	 */
	public function generateUsername($username = '') {

		$username = iconv('UTF-8', 'ASCII//TRANSLIT', $username);

		$blacklist = '/[\x{0080}-\x{009f}\x{00a0}\x{2000}-\x{200f}\x{2028}-\x{202f}\x{3000}\x{e000}-\x{f8ff}]/u';
		$blacklist2 = [
			' ',
			'\'',
			'/',
			'\\',
			'"',
			'*',
			'&',
			'?',
			'#',
			'%',
			'^',
			'(',
			')',
			'{',
			'}',
			'[',
			']',
			'~',
			'?',
			'<',
			'>',
			';',
			'|',
			'¬',
			'`',
			'@',
			'-',
			'+',
			'='
		];
		$username = preg_replace($blacklist, '', $username);
		$username = str_replace($blacklist2, '.', $username);

		return elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($username) {
			$minlength = elgg_get_config('minusername') ? : 4;

			if ($username) {
				$fill = $minlength - strlen($username);
			} else {
				$fill = 8;
			}

			$algo = elgg_get_plugin_setting('autogen_username_algo', 'hypeProfile', 'first_name_only');
			if ($algo == 'full_name' && $fill <= 0) {
				$separator = '.';
			} else {
				$separator = '';
			}

			if ($fill > 0) {
				$suffix = (new \ElggCrypto())->getRandomString($fill);
				$username = "$username$separator$suffix";
			}

			$available = false;
			$iterator = 0;

			while (!$available) {
				if ($iterator > 0) {
					$username = "$username$separator$iterator";
				}

				$user = get_user_by_username($username);
				$available = !$user;

				try {
					if ($available) {
						validate_username($username);
					}
				} catch (Exception $e) {
					if ($iterator >= 100) {
						// too many failed attempts
						$username = (new \ElggCrypto())->getRandomString(8);
					}
				}
				$iterator++;
			}

			return strtolower($username);
		});
	}
}