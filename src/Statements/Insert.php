<?php

declare(strict_types=1);

namespace DB\Statements;

use DB\Contracts\Statement;
use InvalidArgumentException;

/**
 * @author    Deives Fahl <dfahl.cps@gmail.com>
 * @copyright (c) 2022, Deives Fahl
 */
final class Insert implements Statement
{
	/**
	 * Handle with insert statement.
	 *
	 * @access public
	 *
	 * @param array $queries
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return array
	 */
	public function handle(array $queries): array
	{
		if ($queries['select']) {
			throw new InvalidArgumentException('The selection method must not be called together with the insert method');
		}

		if (!$queries['insert']) {
			throw new InvalidArgumentException('The insert method has not been defined');
		}

		$columns = implode(', ', array_keys($queries['insert']));
		$values = implode(', ', array_values($queries['insert']));

		return [
			'query' => "INSERT INTO {$queries['table']} ({$columns}) VALUES ({$values})",
		];
	}
}
