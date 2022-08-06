<?php

declare(strict_types=1);

namespace DB\Contracts;

/**
 * @author    Deives Fahl <dfahl.cps@gmail.com>
 * @copyright (c) 2022, Deives Fahl
 */
interface Statement
{
	/**
	 * Handle with statement.
	 *
	 * @access public
	 *
	 * @param array $queries
	 *
	 * @return array
	 */
	public function handle(array $queries): array;
}
