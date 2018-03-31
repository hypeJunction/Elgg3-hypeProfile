<?php

namespace hypeJunction\Profile;

use ElggEntity;
use hypeJunction\Fields\Field;
use hypeJunction\ValidationException;
use Symfony\Component\HttpFoundation\ParameterBag;

class EmailValidationField extends Field {

	/**
	 * {@inheritdoc}
	 */
	public function validate($value) {
		if ($value) {
			$email = get_input('email');
			$name = get_input('name');

			$token = elgg_build_hmac([
				'email' => $email,
			])->getToken();

			$token = substr($token, 0, 10);

			if (!_elgg_services()->crypto->areEqual($token, $value)) {
				(new SendVerificationCodeAction())->sendCode($email, $name);
				throw new ValidationException(elgg_echo('profile:validation:error'));
			}
		}

		return parent::validate($value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function retrieve(ElggEntity $entity) {

	}

	/**
	 * {@inheritdoc}
	 */
	public function save(ElggEntity $entity, ParameterBag $parameters) {
		/* @var $entity \ElggUser */
		$entity->setValidationStatus(true, 'validation-token');
	}
}