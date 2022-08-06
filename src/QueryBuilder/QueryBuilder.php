<?php

declare(strict_types=1);

namespace DB\QueryBuilder;

use DB\Query\Execute;
use DB\Statements\Delete;
use DB\Statements\Insert;
use DB\Statements\Select;
use DB\Statements\Update;
use InvalidArgumentException;
use PDO;
use stdClass;

/**
 * @author    Deives Fahl <dfahl.cps@gmail.com>
 * @copyright (c) 2022, Deives Fahl
 */
final class QueryBuilder
{
	private $queries = [
		'table' => '',
		'select' => '',
		'distinct' => false,
		'innerJoin' => [],
		'leftJoin' => [],
		'rightJoin' => [],
		'where' => [],
		'orWhere' => [],
		'binds' => [],
		'groupBy' => [],
		'having' => '',
		'orderBy' => [],
		'limit' => 0,
		'offset' => 0,
		'paginate' => false,
		'pagination' => [],
		'insert' => [],
		'update' => [],
	];

	private ?stdClass $pagination = null;

	private static bool $log = false;

	private static array $logQueries = [];

	/**
	 * @access public
	 *
	 * @param PDO $connection
	 *
	 * @return void
	 */
	public function __construct(private PDO $connection)
	{
	}

	/**
	 * Add table.
	 *
	 * @access public
	 *
	 * @param string $table
	 *
	 * @return self
	 */
	public function table(string $table): self
	{
		$this->queries['table'] = $table;

		return $this;
	}

	/**
	 * Add fields for selection.
	 *
	 * @access public
	 *
	 * @param string $fields
	 *
	 * @return self
	 */
	public function select(string $fields = '*'): self
	{
		$this->queries['select'] = $fields;

		return $this;
	}

	/**
	 * Force the query to return distinct results.
	 *
	 * @access public
	 *
	 * @return self
	 */
	public function distinct(): self
	{
		$this->queries['distinct'] = true;

		return $this;
	}

	/**
	 * Add inner join clause.
	 *
	 * @access public
	 *
	 * @param string $table
	 * @param string $primaryKey
	 * @param string $operator
	 * @param string $foreignKey
	 *
	 * @return self
	 */
	public function join(string $table, string $primaryKey, string $operator, string $foreignKey): self
	{
		$this->innerJoin($table, $primaryKey, $operator, $foreignKey);

		return $this;
	}

	/**
	 * Add inner join clause.
	 *
	 * @access public
	 *
	 * @param string $table
	 * @param string $primaryKey
	 * @param string $operator
	 * @param string $foreignKey
	 *
	 * @return self
	 */
	public function innerJoin(string $table, string $primaryKey, string $operator, string $foreignKey): self
	{
		array_push($this->queries['innerJoin'], [
			'table' => $table,
			'primaryKey' => $primaryKey,
			'operator' => $operator,
			'foreignKey' => $foreignKey,
		]);

		return $this;
	}

	/**
	 * Add left join clause.
	 *
	 * @access public
	 *
	 * @param string $table
	 * @param string $primaryKey
	 * @param string $operator
	 * @param string $foreignKey
	 *
	 * @return self
	 */
	public function leftJoin(string $table, string $primaryKey, string $operator, string $foreignKey): self
	{
		array_push($this->queries['leftJoin'], [
			'table' => $table,
			'primaryKey' => $primaryKey,
			'operator' => $operator,
			'foreignKey' => $foreignKey,
		]);

		return $this;
	}

	/**
	 * Add right join clause.
	 *
	 * @access public
	 *
	 * @param string $table
	 * @param string $primaryKey
	 * @param string $operator
	 * @param string $foreignKey
	 *
	 * @return self
	 */
	public function rightJoin(string $table, string $primaryKey, string $operator, string $foreignKey): self
	{
		array_push($this->queries['rightJoin'], [
			'table' => $table,
			'primaryKey' => $primaryKey,
			'operator' => $operator,
			'foreignKey' => $foreignKey,
		]);

		return $this;
	}

	/**
	 * Add where clause using the and operator.
	 *
	 * @access public
	 *
	 * @param mixed $args
	 *
	 * @return self
	 */
	public function where(...$args): self
	{
		$argsQuantity = count($args);

		if ($argsQuantity <= 1 || $argsQuantity > 3) {
			throw new InvalidArgumentException('The number of arguments must be between 2 and 3');
		}

		$fieldHash = uniqid();
		$operator = '=';

		$argsQuantity === 2
			? list($field, $value) = $args
			: list($field, $operator, $value) = $args;

		$this->queries['where'] = array_merge($this->queries['where'], ["{$field} {$operator} :{$field}_{$fieldHash}"]);
		$this->queries['binds'] = array_merge($this->queries['binds'], ["{$field}_{$fieldHash}" => $value]);

		return $this;
	}

	/**
	 * Add where clause using the or operator.
	 *
	 * @access public
	 *
	 * @param mixed $args
	 *
	 * @return self
	 */
	public function orWhere(...$args): self
	{
		$argsQuantity = count($args);

		if ($argsQuantity <= 1 || $argsQuantity > 3) {
			throw new InvalidArgumentException('The number of arguments must be between 2 and 3');
		}

		$fieldHash = uniqid();
		$operator = '=';

		$argsQuantity === 2
			? list($field, $value) = $args
			: list($field, $operator, $value) = $args;

		$this->queries['orWhere'] = array_merge($this->queries['orWhere'], ["{$field} {$operator} :{$field}_{$fieldHash}"]);
		$this->queries['binds'] = array_merge($this->queries['binds'], ["{$field}_{$fieldHash}" => $value]);

		return $this;
	}

	/**
	 * Add group by clause.
	 *
	 * @access public
	 *
	 * @param string $field
	 *
	 * @return self
	 */
	public function groupBy(string $field): self
	{
		array_push($this->queries['groupBy'], "{$field}");

		return $this;
	}

	/**
	 * Add having clause.
	 *
	 * @access public
	 *
	 * @param string $value
	 *
	 * @return self
	 */
	public function having(string $value): self
	{
		$this->queries['having'] = $value;

		return $this;
	}

	/**
	 * Add order by clause
	 *
	 * @access public
	 *
	 * @param string $field
	 * @param mixed $sort
	 *
	 * @return self
	 */
	public function orderBy(string $field, mixed $sort): self
	{
		array_push($this->queries['orderBy'], "{$field} {$sort}");

		return $this;
	}

	/**
	 * Add limit clause.
	 *
	 * @access public
	 *
	 * @param int $limit
	 *
	 * @return self
	 */
	public function limit(int $limit): self
	{
		$this->queries['limit'] = $limit;

		return $this;
	}

	/**
	 * Add offset clause.
	 *
	 * @access public
	 *
	 * @param int $offset
	 *
	 * @return self
	 */
	public function offset(int $offset): self
	{
		$this->queries['offset'] = $offset;

		return $this;
	}

	/**
	 * Paginate queries.
	 *
	 * @access public
	 *
	 * @param int $limit
	 * @param array $options
	 *
	 * @return array|null
	 */
	public function paginate(int $limit = 2, array $options = []): ?array
	{
		$path = $options['path'] ?? '';
		$perPage = (int) ($options['perPage'] ?? 5);
		$currentPage = isset($options['currentPage']) ? (int) $options['currentPage'] : (int) ($_GET['page'] ?? 1);

		!$this->queries['limit']
			? $this->queries['limit'] = $limit
			: $limit = $this->queries['limit'];

		$this->queries['select'] = 'SQL_CALC_FOUND_ROWS ' . $this->queries['select'];
		$this->queries['offset'] = ($currentPage - 1) * $limit;
		$this->queries['paginate'] = true;
		$this->queries['pagination'] = array_merge($options, [
			'path' => $path,
			'perPage' => $perPage,
			'currentPage' => $currentPage,
		]);

		return $this->get();
	}

	/**
	 * Get paginate links.
	 *
	 * @access public
	 *
	 * @return stdClass|null
	 */
	public function pagination(): ?stdClass
	{
		return $this->pagination;
	}

	/**
	 * Get query result.
	 *
	 * @access public
	 *
	 * @return array|null
	 */
	public function get(): ?array
	{
		$execute = new Execute($this->connection);
		$execute->setQuery($this->queries);

		$result = $execute->execute(new Select());
		$rows = $result['rows'];
		$pagination = $result['pagination'] ?? null;
		$sql = $result['sql'];
		$time = $result['time'];

		$this->pagination = $pagination;

		$this->addQueryLog($sql, $time);

		$this->reset();

		return count($rows) > 0 ? $rows : null;
	}

	/**
	 * Get first query result.
	 *
	 * @access public
	 *
	 * @return stdClass|array|null
	 */
	public function first(): stdClass|array|null
	{
		$execute = new Execute($this->connection);
		$execute->setQuery($this->queries);

		$result = $execute->execute(new Select(false));
		$rows = $result['rows'];
		$sql = $result['sql'];
		$time = $result['time'];

		$this->addQueryLog($sql, $time);

		$this->reset();

		return $rows ? $rows : null;
	}

	/**
	 * Insert records into the database table.
	 *
	 * @access public
	 *
	 * @param array $fields
	 *
	 * @return boolean
	 */
	public function insert(array $fields): bool
	{
		$this->prepareInsert($fields);

		$executed = $this->executeInsert();

		$this->reset();

		return $executed['result'];
	}

	/**
	 * Insert records into the database table and retrieve the id.
	 *
	 * @access public
	 *
	 * @param array $fields
	 *
	 * @return int
	 */
	public function insertGetId(array $fields): int
	{
		$this->prepareInsert($fields);

		$executed = $this->executeInsert();

		$this->reset();

		return $executed['id'];
	}

	/**
	 * Prepare insert.
	 *
	 * @access private
	 *
	 * @param array $fields
	 *
	 * @return void
	 */
	private function prepareInsert(array $fields): void
	{
		foreach (array_keys($fields) as $field) {
			$this->queries['insert'][$field] = ":{$field}";
		}

		$this->queries['binds'] = array_merge($this->queries['binds'], $fields);
	}

	/**
	 * Execute insert.
	 *
	 * @access private
	 *
	 * @return array
	 */
	private function executeInsert(): array
	{
		$execute = new Execute($this->connection);
		$execute->setQuery($this->queries);

		return $execute->execute(new Insert());
	}

	/**
	 * Update records into the database table.
	 *
	 * @access public
	 *
	 * @param array $fields
	 * @param boolean $withoutRestrictions
	 *
	 * @return boolean
	 */
	public function update(array $fields, bool $withoutRestrictions = false): bool
	{
		$this->prepareUpdate($fields);

		$executed = $this->executeUpdate($withoutRestrictions);

		$this->reset();

		return $executed['result'];
	}

	/**
	 * Prepare update.
	 *
	 * @access private
	 *
	 * @param array $fields
	 *
	 * @return void
	 */
	private function prepareUpdate(array $fields): void
	{
		$this->queries['update'] = array_merge(
			$this->queries['update'],
			array_map(fn ($field) => "{$field} = :{$field}", array_keys($fields))
		);

		$this->queries['binds'] = array_merge($this->queries['binds'], $fields);
	}

	/**
	 * Execute update.
	 *
	 * @access private
	 *
	 * @param boolean $withoutRestrictions = false
	 *
	 * @return array
	 */
	private function executeUpdate(bool $withoutRestrictions = false): array
	{
		$execute = new Execute($this->connection);
		$execute->setQuery($this->queries);

		return $execute->execute(new Update($withoutRestrictions));
	}

	/**
	 * Delete records into the database table.
	 *
	 * @access public
	 *
	 * @param boolean $withoutRestrictions
	 *
	 * @return boolean
	 */
	public function delete(bool $withoutRestrictions = false): bool
	{
		$executed = $this->executeDelete($withoutRestrictions);

		$this->reset();

		return $executed['result'];
	}

	/**
	 * Execute delete.
	 *
	 * @access private
	 *
	 * @param boolean $withoutRestrictions
	 *
	 * @return array
	 */
	private function executeDelete(bool $withoutRestrictions = false): array
	{
		$execute = new Execute($this->connection);
		$execute->setQuery($this->queries);

		return $execute->execute(new Delete($withoutRestrictions));
	}

	/**
	 * Enable query log.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public static function enableQueryLog(): void
	{
		self::$log = true;
	}

	/**
	 * Disable query log.
	 *
	 * @access public
	 *
	 * @return void
	 */
	private static function disableQueryLog(): void
	{
		self::$log = false;
	}

	/**
	 * Add query log.
	 *
	 * @access private
	 *
	 * @param string $query
	 * @param float $time
	 *
	 * @return void
	 */
	private function addQueryLog(string $query, float $time = 0): void
	{
		if (!self::$log) {
			return;
		}

		$binds = $this->queries['binds'];
		$bindsPlaceholders = array_map(fn ($bind) => ":{$bind}", array_keys($binds));
		$bindsValues = array_map(fn ($value) => is_numeric($value) ? $value : "'{$value}'", array_values($binds));
		$queryWithBindings = str_replace($bindsPlaceholders, $bindsValues, $query);

		array_push(self::$logQueries, [
			'query' => $query,
			'queryWithBindings' => $queryWithBindings,
			'bindings' => $binds,
			'time' => $time,
		]);
	}

	/**
	 * Get query log.
	 *
	 * @access public
	 *
	 * @return array
	 */
	public static function getQueryLog(): array
	{
		if (!self::$log) {
			return [];
		}

		self::disableQueryLog();

		return self::$logQueries;
	}

	/**
	 * Returns the settings to the initial value.
	 *
	 * @access private
	 *
	 * @return void
	 */
	private function reset(): void
	{
		$this->queries = [
			'table' => '',
			'select' => '',
			'distinct' => false,
			'innerJoin' => [],
			'leftJoin' => [],
			'rightJoin' => [],
			'where' => [],
			'orWhere' => [],
			'binds' => [],
			'groupBy' => [],
			'having' => '',
			'orderBy' => [],
			'limit' => 0,
			'offset' => 0,
			'paginate' => false,
			'pagination' => [],
			'insert' => [],
			'update' => [],
		];
	}
}
