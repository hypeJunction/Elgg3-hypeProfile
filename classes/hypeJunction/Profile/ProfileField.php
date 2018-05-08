<?php

namespace hypeJunction\Profile;

use ElggEntity;
use hypeJunction\Fields\Field;
use Symfony\Component\HttpFoundation\ParameterBag;

class ProfileField extends Field {

	const FIELD_PICKER = 'field_picker';
	const GLOBAL_PICKER = 'global_picker';
	const FORCE_PUBLIC = 'public';
	const FORCE_LOGGED_IN = 'logged_in';

	/**
	 * {@inheritdoc}
	 */
	public function render(\ElggEntity $entity, $context = null) {
		if (!$this->isVisible($entity, $context)) {
			return '';
		}

		if (!elgg_view_exists("input/$this->type")) {
			return '';
		}

		$main = $this->normalize($entity);

		$annotations = $this->getAnnotation($entity);

		if ($context == self::CONTEXT_EDIT_FORM) {
			$field_access_conf = elgg_get_plugin_setting('field_access', 'hypeProfile');

			if ($field_access_conf == self::FIELD_PICKER) {
				$class = elgg_extract_class($main, '', '#class');
				unset($main['#class']);

				$fields = [$main];
				$fields[] = [
					'#type' => 'access',
					'name' => "accesslevel[$this->name]",
					'value' => $annotations ? $annotations[0]->access_id : get_default_access($entity),
					'entity' => $entity,
					'class' => 'profile-access-input-field',
				];

				return elgg_view_field([
					'#type' => 'fieldset',
					'#class' => $class,
					'fields' => $fields,
				]);
			} else {
				return elgg_view_field($main);
			}
		} else {
			return elgg_view_field($main);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function retrieve(ElggEntity $entity) {
		$annotations = $this->getAnnotation($entity);

		$values = array_map(function (\ElggAnnotation $a) {
			return $a->value;
		}, $annotations);

		if ($values) {
			return (count($values) === 1) ? $values[0] : $values;
		}

		return null;
	}

	/**
	 * Get profile field annotations
	 *
	 * @param ElggEntity $entity Entity
	 *
	 * @return array
	 */
	public function getAnnotation(ElggEntity $entity) {
		$annotations = $entity->getAnnotations([
			'annotation_names' => "profile:{$this->name}",
			'limit' => false,
		]);

		return $annotations ? : [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function save(ElggEntity $entity, ParameterBag $parameters) {
		$value = $parameters->get($this->name);

		if (is_array($value)) {
			array_walk_recursive($value, function (&$v) {
				$v = elgg_html_decode($v);
			});
		} else {
			$value = elgg_html_decode($value);
		}

		$entity->deleteAnnotations("profile:{$this->name}");

		elgg_delete_metadata([
			'guid' => $entity->guid,
			'metadata_name' => $this->name,
			'limit' => false
		]);

		$field_access_conf = elgg_get_plugin_setting('field_access', 'hypeProfile');

		if (!is_null($value) && ($value !== '')) {
			$access_id = ACCESS_PRIVATE;

			switch ($field_access_conf) {
				case ProfileField::GLOBAL_PICKER :
					$access_id = $parameters->get('profile_field_access', ACCESS_PRIVATE);
					break;

				case ProfileField::FIELD_PICKER :
					$access = get_input('accesslevel') ? : [];
					if (isset($access[$this->name])) {
						$access_id = (int) $access[$this->name];
					}
					break;

				case ProfileField::FORCE_PUBLIC :
					$access_id = ACCESS_PUBLIC;
					break;

				case ProfileField::FORCE_LOGGED_IN :
					$access_id = ACCESS_LOGGED_IN;
					break;
			}

			if (!is_array($value)) {
				$value = [$value];
			}

			foreach ($value as $interval) {
				$entity->annotate("profile:{$this->name}", $interval, $access_id, $entity->guid);
			}

			$entity->{"{$this->name}"} = $value;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function label(ElggEntity $entity) {
		return elgg_echo("profile:{$this->name}");
	}
}
