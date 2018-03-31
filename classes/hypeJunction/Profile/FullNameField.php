<?php

namespace hypeJunction\Profile;

use ElggEntity;
use hypeJunction\Fields\Field;
use hypeJunction\ValidationException;
use Symfony\Component\HttpFoundation\ParameterBag;

class FullNameField extends Field {

	/**
	 * {@inheritdoc}
	 */
	public function validate($value) {
		if (empty($value['first_name']) || empty($value['last_name'])) {
			throw new ValidationException(elgg_echo('validation:error:required'));
		}

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function retrieve(ElggEntity $entity) {
		if ($entity->first_name || $entity->last_name) {
			return [
				'first_name' => $entity->first_name,
				'last_name' => $entity->last_name,
			];
		} else if ($entity->name) {
			list($first_name, $last_name) = explode(' ', $entity->name, 2);

			return [
				'first_name' => $first_name,
				'last_name' => $last_name,
			];
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function save(ElggEntity $entity, ParameterBag $parameters) {
		$value = $parameters->get($this->name);

		$entity->first_name = ucfirst(elgg_extract('first_name', $value));
		$entity->last_name = ucfirst(elgg_extract('last_name', $value));
		$entity->last_name_abbr = substr($entity->last_name, 0, 1) . '.';

		$entity->name = substr(trim("$entity->first_name $entity->last_name_abbr"), 0, 50);
	}
}
