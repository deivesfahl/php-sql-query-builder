<?php

declare(strict_types=1);

namespace DB\Statements;

use DB\Contracts\Statement;
use InvalidArgumentException;

/**
 * @author    Deives Fahl <dfahl.cps@gmail.com>
 * @copyright (c) 2022, Deives Fahl
 */
final class Select implements Statement
{
	/**
	 * @access public
	 *
	 * @param array $queries
	 *
	 * @return void
	 */
	public function __construct(private bool $fetchAll = true)
	{
	}

	/**
	 * Handle with select statement.
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
		if (!$queries['select']) {
			throw new InvalidArgumentException('The select method has not been defined');
		}

		$distinct = $queries['distinct'] ? ' DISTINCT' : '';

		return [
			'query' => "SELECT{$distinct} {$queries['select']} FROM {$queries['table']}",
			'fetchAll' => $this->fetchAll,
		];
	}
}
