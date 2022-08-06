<?php

declare(strict_types=1);

namespace DB\Builders\Clauses;

use DB\Contracts\ClauseBuilder;

/**
 * @author    Deives Fahl <dfahl.cps@gmail.com>
 * @copyright (c) 2022, Deives Fahl
 */
final class InnerJoin implements ClauseBuilder
{
	/**
	 * Build the clause.
	 *
	 * @access public
	 *
	 * @param array $queries
	 *
	 * @return string
	 */
	public static function build(array $queries): string
	{
		if (!$queries['innerJoin']) {
			return '';
		}

		$query = [];

		foreach ($queries['innerJoin'] as $join) {
			$table = $join['table'] ?? '';
			$primaryKey = $join['primaryKey'] ?? '';
			$operator = $join['operator'] ?? '';
			$foreignKey = $join['foreignKey'] ?? '';

			$query[] = sprintf('INNER JOIN %s ON %s %s %s', $table, $primaryKey, $operator, $foreignKey);
		}

		return ' ' . implode(' ', $query);
	}
}
