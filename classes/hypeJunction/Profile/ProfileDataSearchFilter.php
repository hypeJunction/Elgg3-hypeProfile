<?php

namespace hypeJunction\Profile;

use Elgg\Database\Clauses\WhereClause;
use Elgg\Database\QueryBuilder;
use hypeJunction\Lists\FilterInterface;

class ProfileDataSearchFilter implements FilterInterface {


	/**
	 * Returns ID of the filter
	 * @return string
	 */
	public static function id() {
		return 'profile';
	}

	/**
	 * Build a filtering clause
	 *
	 * @param       $target \ElggEntity Target entity of the filtering relationship
	 * @param array $params Filter params
	 *
	 * @return WhereClause|null
	 */
	public static function build(\ElggEntity $target = null, array $params = []) {

		$profile = (array) elgg_extract('profile', $params, []);

		$handler = function (QueryBuilder $qb) use ($profile) {

			$ands = [];

			foreach ($profile as $key => $value) {
				if ($key && $value) {
					$alias = $qb->joinMetadataTable('e', 'guid', $key);
					$ors = [];
					if (is_array($value)) {
						foreach ($value as $val) {
							if (empty($val)) {
								continue;
							}
							$ors[] = $qb->compare("$alias.value", 'LIKE', "%$val%", ELGG_VALUE_STRING);
						}
					} else {
						foreach ($value as $val) {
							$ands[] = $qb->compare("$alias.value", 'LIKE', "%$value%", ELGG_VALUE_STRING);
						}
					}
					
					$ands[] = $qb->compare("$alias.value", '!=', '', ELGG_VALUE_STRING);
					$ands[] = $qb->merge($ors, 'OR');
				}
			}

			return $qb->merge($ands);
		};

		return new WhereClause($handler);
	}
}