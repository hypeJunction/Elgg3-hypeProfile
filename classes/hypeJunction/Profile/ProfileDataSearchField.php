<?php

namespace hypeJunction\Profile;

use hypeJunction\Lists\SearchFields\SearchField;

class ProfileDataSearchField extends SearchField {

	/**
	 * Returns field name
	 * @return string
	 */
	public function getName() {
		return 'profile';
	}

	/**
	 * Returns field parameters
	 * @return array|null
	 */
	public function getField() {
		$name = $this->getName();
		$value = $this->getValue() ? : [];

		return [
			'#type' => 'search/profile_data',
			'name' => $name,
			'value' => $value,
		];
	}

	/**
	 * Set constraints on the collection based on field value
	 * @return void
	 */
	public function setConstraints() {
		$value = $this->getValue();
		if (!$value) {
			return;
		}

		$this->collection->addFilter(ProfileDataSearchFilter::class, null, [
			'profile' => $value,
		]);
	}
}