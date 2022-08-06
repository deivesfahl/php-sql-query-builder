<?php

declare(strict_types=1);

namespace DB\Contracts;

/**
 * @author    Deives Fahl <dfahl.cps@gmail.com>
 * @copyright (c) 2022, Deives Fahl
 */
interface ClauseBuilder
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
	public static function build(array $queries): string;
}
