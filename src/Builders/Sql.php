<?php

declare(strict_types=1);

namespace DB\Builders;

use DB\Builders\Clauses\GroupBy;
use DB\Builders\Clauses\Having;
use DB\Builders\Clauses\InnerJoin;
use DB\Builders\Clauses\LeftJoin;
use DB\Builders\Clauses\Limit;
use DB\Builders\Clauses\Offset;
use DB\Builders\Clauses\OrderBy;
use DB\Builders\Clauses\OrWhere;
use DB\Builders\Clauses\RightJoin;
use DB\Builders\Clauses\Where;

/**
 * @author    Deives Fahl <dfahl.cps@gmail.com>
 * @copyright (c) 2022, Deives Fahl
 */
final class Sql
{
	/**
	 * Build sql.
	 *
	 * @access public
	 *
	 * @param string $sql
	 * @param array $queries
	 *
	 * @return string
	 */
	public static function builder(string $sql, array $queries): string
	{
		$sql .= InnerJoin::build($queries);
		$sql .= LeftJoin::build($queries);
		$sql .= RightJoin::build($queries);
		$sql .= Where::build($queries);
		$sql .= OrWhere::build($queries);
		$sql .= GroupBy::build($queries);
		$sql .= Having::build($queries);
		$sql .= OrderBy::build($queries);
		$sql .= Limit::build($queries);
		$sql .= Offset::build($queries);

		return $sql;
	}
}
