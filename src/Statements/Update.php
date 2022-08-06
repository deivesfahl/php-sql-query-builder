<?php

declare(strict_types=1);

namespace DB\Statements;

use DB\Contracts\Statement;
use InvalidArgumentException;

/**
 * @author    Deives Fahl <dfahl.cps@gmail.com>
 * @copyright (c) 2022, Deives Fahl
 */
final class Update implements Statement
{
	/**
	 * @access public
	 *
	 * @param bool $withoutRestrictions
	 *
	 * @return void
	 */
	public function __construct(private bool $withoutRestrictions = false)
	{
	}

	/**
	 * Handle with update statement.
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
			throw new InvalidArgumentException('The selection method must not be called together with the update method');
		}

		if (!$queries['update']) {
			throw new InvalidArgumentException('The update method has not been defined');
		}

		if (!$queries['where'] && !$this->withoutRestrictions) {
			throw new InvalidArgumentException('The where method has not been defined');
		}

		$set = implode(', ', $queries['update']);

		return [
			'query' => "UPDATE {$queries['table']} SET {$set}",
		];
	}
}
