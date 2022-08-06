<?php

declare(strict_types=1);

namespace DB\Query;

use DB\Builders\Sql;
use DB\Contracts\Statement;
use DB\Paginator\Paginator;
use DB\Statements\Delete;
use DB\Statements\Insert;
use DB\Statements\Update;
use PDO;
use PDOStatement;

/**
 * @author    Deives Fahl <dfahl.cps@gmail.com>
 * @copyright (c) 2022, Deives Fahl
 */
final class Execute
{
	private array $queries = [];

	/**
	 * @access public
	 *
	 * @return void
	 */
	public function __construct(private PDO $connection)
	{
	}

	/**
	 * Set query.
	 *
	 * @access public
	 *
	 * @param array $queries
	 *
	 * @return void
	 */
	public function setQuery(array $queries): void
	{
		$this->queries = $queries;
	}

	/**
	 * Execute statement.
	 *
	 * @access public
	 *
	 * @param Statement $builder
	 *
	 * @return array|bool
	 */
	public function execute(Statement $builder): array|bool
	{
		$result = $builder->handle($this->queries);

		$sql = Sql::builder($result['query'], $this->queries);

		$executionTimeStart = microtime(true);

		$prepare = $this->connection->prepare($sql);
		$executed = $prepare->execute($this->queries['binds']);

		$executionTimeEnd = microtime(true) - $executionTimeStart;

		if ($builder instanceof Insert) {
			return [
				'result' => $executed,
				'id' => (int) $this->connection->lastInsertId(),
				'sql' => $sql,
				'time' => $executionTimeEnd,
			];
		}

		if ($builder instanceof Update || $builder instanceof Delete) {
			return [
				'result' => $executed,
				'sql' => $sql,
				'time' => $executionTimeEnd,
			];
		}

		return array_merge($this->select($result, $prepare), [
			'sql' => $sql,
			'time' => $executionTimeEnd,
		]);
	}

	/**
	 * Select statement.
	 *
	 * @access public
	 *
	 * @param array $result
	 * @param PDOStatement|false $prepare
	 *
	 * @return array
	 */
	private function select(array $result, PDOStatement|false $prepare): array
	{
		$fetch = $result['fetchAll'] ? $prepare->fetchAll() : $prepare->fetch();

		if ($this->queries['paginate']) {
			return array_merge(['rows' => $fetch], $this->selectWithPagination());
		}

		return [
			'rows' => $fetch,
		];
	}

	/**
	 * Select statement with pagination.
	 *
	 * @access public
	 *
	 * @return array
	 */
	private function selectWithPagination(): array
	{
		$pagination = $this->queries['pagination'];
		$count = $this->connection->query('SELECT FOUND_ROWS()')->fetchColumn();
		$limit = $this->queries['limit'];
		$totalPages = (int) number_format(ceil((int) $count / $limit));
		$path = $pagination['path'];
		$perPage = $pagination['perPage'];
		$currentPage = $pagination['currentPage'];
		$listClass = $pagination['listClass'] ?? false;
		$listItemClass = $pagination['listItemClass'] ?? false;
		$listItemActiveClass = $pagination['listItemActiveClass'] ?? false;
		$listItemDisabledClass = $pagination['listItemDisabledClass'] ?? false;
		$listLinkClass = $pagination['listLinkClass'] ?? false;
		$listLinkAriaLabelFirst = $pagination['listLinkAriaLabelFirst'] ?? false;
		$listLinkLabelFirst = $pagination['listLinkLabelFirst'] ?? false;
		$listLinkAriaLabelPrevious = $pagination['listLinkAriaLabelPrevious'] ?? false;
		$listLinkLabelPrevious = $pagination['listLinkLabelPrevious'] ?? false;
		$listLinkAriaLabel = $pagination['listLinkAriaLabel'] ?? false;
		$listLinkAriaLabelNext = $pagination['listLinkAriaLabelNext'] ?? false;
		$listLinkLabelNext = $pagination['listLinkLabelNext'] ?? false;
		$listLinkAriaLabelLast = $pagination['listLinkAriaLabelLast'] ?? false;
		$listLinkLabelLast = $pagination['listLinkLabelLast'] ?? false;

		$paginator = new Paginator($totalPages, $perPage, $currentPage, $path);

		if ($listClass !== false && is_string($listClass)) {
			$paginator->setListClass($listClass);
		}

		if ($listItemClass !== false && is_string($listItemClass)) {
			$paginator->setListItemClass($listItemClass);
		}

		if ($listItemActiveClass !== false && is_string($listItemActiveClass)) {
			$paginator->setListItemActiveClass($listItemActiveClass);
		}

		if ($listItemDisabledClass !== false && is_string($listItemDisabledClass)) {
			$paginator->setListItemDisabledClass($listItemDisabledClass);
		}

		if ($listLinkClass !== false && is_string($listLinkClass)) {
			$paginator->setListLinkClass($listLinkClass);
		}

		if ($listLinkAriaLabelFirst !== false && is_string($listLinkAriaLabelFirst)) {
			$paginator->setListLinkAriaLabelFirst($listLinkAriaLabelFirst);
		}

		if ($listLinkLabelFirst !== false && is_string($listLinkLabelFirst)) {
			$paginator->setListLinkLabelFirst($listLinkLabelFirst);
		}

		if ($listLinkAriaLabelPrevious !== false && is_string($listLinkAriaLabelPrevious)) {
			$paginator->setListLinkAriaLabelPrevious($listLinkAriaLabelPrevious);
		}

		if ($listLinkLabelPrevious !== false && is_string($listLinkLabelPrevious)) {
			$paginator->setListLinkLabelPrevious($listLinkLabelPrevious);
		}

		if ($listLinkAriaLabel !== false && is_string($listLinkAriaLabel)) {
			$paginator->setListLinkAriaLabel($listLinkAriaLabel);
		}

		if ($listLinkAriaLabelNext !== false && is_string($listLinkAriaLabelNext)) {
			$paginator->setListLinkAriaLabelNext($listLinkAriaLabelNext);
		}

		if ($listLinkLabelNext !== false && is_string($listLinkLabelNext)) {
			$paginator->setListLinkLabelNext($listLinkLabelNext);
		}

		if ($listLinkAriaLabelLast !== false && is_string($listLinkAriaLabelLast)) {
			$paginator->setListLinkAriaLabelLast($listLinkAriaLabelLast);
		}

		if ($listLinkLabelLast !== false && is_string($listLinkLabelLast)) {
			$paginator->setListLinkLabelLast($listLinkLabelLast);
		}

		$paginator->handle();

		return [
			'pagination' => (object) [
				'total' => $totalPages,
				'perPage' => $perPage,
				'currentPage' => $currentPage,
				'lastPage' => $paginator->getTotalPages(),
				'path' => $path,
				'firstPageUrl' => $paginator->getFirstPageUrl(),
				'previousPageUrl' => $paginator->getPreviousPageUrl(),
				'nextPageUrl' => $paginator->getNextPageUrl(),
				'lastPageUrl' => $paginator->getLastPageUrl(),
				'links' => $paginator->getLinks(),
			],
		];
	}
}
