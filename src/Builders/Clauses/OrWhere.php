<?php

declare(strict_types=1);

namespace DB\Builders\Clauses;

use DB\Contracts\ClauseBuilder;

/**
 * @author    Deives Fahl <dfahl.cps@gmail.com>
 * @copyright (c) 2022, Deives Fahl
 */
final class OrWhere implements ClauseBuilder
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
		if (!$queries['orWhere']) {
			return '';
		}

		$query = ' OR ';

		foreach ($queries['orWhere'] as $where) {
			$query .= $where . ' OR ';
		}

		$query = preg_replace('/ OR $/', '', $query);

		return $query;
	}
}
