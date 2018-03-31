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
					$ands[] = $qb->compare("$alias.value", 'LIKE', "%$value%", ELGG_VALUE_STRING);
				}
			}

			return $qb->merge($ands);
		};

		return new WhereClause($handler);
	}
}