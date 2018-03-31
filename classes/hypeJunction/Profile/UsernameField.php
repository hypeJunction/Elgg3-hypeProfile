<?php

namespace hypeJunction\Profile;

use hypeJunction\Fields\MetaField;
use hypeJunction\ValidationException;

class UsernameField extends MetaField {

	/**
	 * {@inheritdoc}
	 */
	public function validate($value) {
		try {
			validate_username($value);
		} catch (\Exception $e) {
			throw new ValidationException($e->getMessage());
		}
	}

}
