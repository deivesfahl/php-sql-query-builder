<?php

declare(strict_types=1);

namespace DB\Builders\Clauses;

use DB\Contracts\ClauseBuilder;

/**
 * @author    Deives Fahl <dfahl.cps@gmail.com>
 * @copyright (c) 2022, Deives Fahl
 */
final class Where implements ClauseBuilder
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
		if (!$queries['where']) {
			return '';
		}

		$query = ' WHERE ';

		foreach ($queries['where'] as $where) {
			$query .= $where . ' AND ';
		}

		$query = preg_replace('/ AND $/', '', $query);

		return $query;
	}
}
