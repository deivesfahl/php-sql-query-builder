<?php

declare(strict_types=1);

namespace DB\Paginator;

/**
 * @author    Deives Fahl <dfahl.cps@gmail.com>
 * @copyright (c) 2022, Deives Fahl
 */
final class Paginator
{
	private string $firstPageUrl = '';

	private int $previousPage = 0;

	private string $previousPageUrl = '';

	private int $nextPage = 0;

	private string $nextPageUrl = '';

	private string $lastPageUrl = '';

	private string $listClass = 'pagination';

	private string $listItemClass = 'page-item';

	private string $listItemActiveClass = 'active';

	private string $listItemDisabledClass = 'disabled';

	private string $listLinkClass = 'page-link';

	private string $listLinkAriaLabelFirst = 'Go to first page';

	private string $listLinkLabelFirst = 'First';

	private string $listLinkAriaLabelPrevious = 'Go to previous page';

	private string $listLinkLabelPrevious = 'Previous';

	private string $listLinkAriaLabel = 'Go to page %s';

	private string $listLinkAriaLabelNext = 'Go to next page';

	private string $listLinkLabelNext = 'Next';

	private string $listLinkAriaLabelLast = 'Go to last page';

	private string $listLinkLabelLast = 'Last';

	private string $links;

	/**
	 * @access public
	 *
	 * @param int $totalPages
	 * @param int $perPage
	 * @param int $currentPage
	 * @param string $path
	 *
	 * @return void
	 */
	public function __construct(
		private int $totalPages,
		private int $perPage,
		private int $currentPage,
		private string $path
	) {
	}

	/**
	 * @access public
	 *
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->links;
	}

	/**
	 * Set class from list.
	 *
	 * @param string $listClass
	 *
	 * @return self
	 */
	public function setListClass(string $listClass): self
	{
		$this->listClass = $listClass;

		return $this;
	}

	/**
	 * Set class from item of list.
	 *
	 * @param string $listItemClass
	 *
	 * @return self
	 */
	public function setListItemClass(string $listItemClass): self
	{
		$this->listItemClass = $listItemClass;

		return $this;
	}

	/**
	 * Set active class from item of list.
	 *
	 * @param string $listItemActiveClass
	 *
	 * @return self
	 */
	public function setListItemActiveClass(string $listItemActiveClass): self
	{
		$this->listItemActiveClass = $listItemActiveClass;

		return $this;
	}

	/**
	 * Set disabled class from item of list.
	 *
	 * @param string $listItemDisabledClass
	 *
	 * @return self
	 */
	public function setListItemDisabledClass(string $listItemDisabledClass): self
	{
		$this->listItemDisabledClass = $listItemDisabledClass;

		return $this;
	}

	/**
	 * Set class from item link of list.
	 *
	 * @param string $listLinkClass
	 *
	 * @return self
	 */
	public function setListLinkClass(string $listLinkClass): self
	{
		$this->listLinkClass = $listLinkClass;

		return $this;
	}

	/**
	 * Set aria label attribute from first link of list.
	 *
	 * @param string $listLinkAriaLabelFirst
	 *
	 * @return self
	 */
	public function setListLinkAriaLabelFirst(string $listLinkAriaLabelFirst): self
	{
		$this->listLinkAriaLabelFirst = $listLinkAriaLabelFirst;

		return $this;
	}

	/**
	 * Set label from first link of list.
	 *
	 * @param string $listLinkLabelFirst
	 *
	 * @return self
	 */
	public function setListLinkLabelFirst(string $listLinkLabelFirst): self
	{
		$this->listLinkLabelFirst = $listLinkLabelFirst;

		return $this;
	}

	/**
	 * Set aria label attribute from previous link of list.
	 *
	 * @param string $listLinkAriaLabelPrevious
	 *
	 * @return self
	 */
	public function setListLinkAriaLabelPrevious(string $listLinkAriaLabelPrevious): self
	{
		$this->listLinkAriaLabelPrevious = $listLinkAriaLabelPrevious;

		return $this;
	}

	/**
	 * Set label from previous link of list.
	 *
	 * @param string $listLinkLabelPrevious
	 *
	 * @return self
	 */
	public function setListLinkLabelPrevious(string $listLinkLabelPrevious): self
	{
		$this->listLinkLabelPrevious = $listLinkLabelPrevious;

		return $this;
	}

	/**
	 * Set aria label attribute from link of list.
	 *
	 * @param string $listLinkAriaLabel
	 *
	 * @return self
	 */
	public function setListLinkAriaLabel(string $listLinkAriaLabel): self
	{
		$this->listLinkAriaLabel = $listLinkAriaLabel;

		return $this;
	}

	/**
	 * Set aria label attribute from next link of list.
	 *
	 * @param string $listLinkAriaLabelNext
	 *
	 * @return self
	 */
	public function setListLinkAriaLabelNext(string $listLinkAriaLabelNext): self
	{
		$this->listLinkAriaLabelNext = $listLinkAriaLabelNext;

		return $this;
	}

	/**
	 * Set label from next link of list.
	 *
	 * @param string $listLinkLabelNext
	 *
	 * @return self
	 */
	public function setListLinkLabelNext(string $listLinkLabelNext): self
	{
		$this->listLinkLabelNext = $listLinkLabelNext;

		return $this;
	}

	/**
	 * Set aria label attribute from last link of list.
	 *
	 * @param string $listLinkAriaLabelLast
	 *
	 * @return self
	 */
	public function setListLinkAriaLabelLast(string $listLinkAriaLabelLast): self
	{
		$this->listLinkAriaLabelLast = $listLinkAriaLabelLast;

		return $this;
	}

	/**
	 * Set label from last link of list.
	 *
	 * @param string $listLinkLabelLast
	 *
	 * @return self
	 */
	public function setListLinkLabelLast(string $listLinkLabelLast): self
	{
		$this->listLinkLabelLast = $listLinkLabelLast;

		return $this;
	}

	/**
	 * Get first page url.
	 *
	 * @access public
	 *
	 * @return string
	 */
	public function getFirstPageUrl(): string
	{
		return $this->firstPageUrl;
	}

	/**
	 * Get previous page url.
	 *
	 * @access public
	 *
	 * @return string
	 */
	public function getPreviousPageUrl(): string
	{
		return $this->previousPageUrl;
	}

	/**
	 * Get next page url.
	 *
	 * @access public
	 *
	 * @return string
	 */
	public function getNextPageUrl(): string
	{
		return $this->nextPageUrl;
	}

	/**
	 * Get total pages.
	 *
	 * @access public
	 *
	 * @return int
	 */
	public function getTotalPages(): int
	{
		return $this->totalPages;
	}

	/**
	 * Get last page url.
	 *
	 * @access public
	 *
	 * @return string
	 */
	public function getLastPageUrl(): string
	{
		return $this->lastPageUrl;
	}

	/**
	 * Get links.
	 *
	 * @access public
	 *
	 * @return string
	 */
	public function getLinks(): string
	{
		return $this->links;
	}

	/**
	 * Handle pagination links.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function handle(): void
	{
		$startLinks = $this->startLinks();
		$endLinks = $this->endLinks();
		$url = "{$this->path}?{$this->queryString()}";

		$this->links = '<ul class="' . $this->listClass . '">';
		$this->links .= $this->firstAndPreviousLinks($url);
		$this->links .= $this->pageLinks($startLinks, $endLinks, $url);
		$this->links .= $this->nextAndLastLinks($url);
		$this->links .= '</ul>';
	}

	/**
	 * Start links.
	 *
	 * @access private
	 *
	 * @return int
	 */
	private function startLinks(): int
	{
		if ($this->currentPage > $this->perPage) {
			return $this->currentPage - $this->perPage;
		}

		return 1;
	}

	/**
	 * End links.
	 *
	 * @access private
	 *
	 * @return int
	 */
	private function endLinks(): int
	{
		if (($this->currentPage + $this->perPage) < $this->totalPages) {
			return $this->currentPage + $this->perPage;
		}

		return $this->totalPages;
	}

	/**
	 * Build first and previous links.
	 *
	 * @access private
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	private function firstAndPreviousLinks(string $url): string
	{
		$links = '';

		$firstPageUrl = '#';
		$previousPageUrl = '#';
		$disabled = $this->listItemDisabledClass;

		if ($this->currentPage > 1) {
			$this->firstPageUrl = "{$url}page=1";
			$this->previousPage = $this->currentPage - 1;
			$this->previousPageUrl = "{$url}page={$this->previousPage}";

			$firstPageUrl = $this->firstPageUrl;
			$previousPageUrl = $this->previousPageUrl;
			$disabled = '';
		}

		$format = '<li class="%s"><a class="%s" href="%s" aria-label="%s">%s</a></li>';

		$links .= sprintf(
			$format,
			trim($this->listItemClass . ' ' . $disabled),
			$this->listLinkClass,
			$firstPageUrl,
			$this->listLinkAriaLabelFirst,
			$this->listLinkLabelFirst
		);

		$links .= sprintf(
			$format,
			trim($this->listItemClass . ' ' . $disabled),
			$this->listLinkClass,
			$previousPageUrl,
			$this->listLinkAriaLabelPrevious,
			$this->listLinkLabelPrevious
		);

		return $links;
	}

	/**
	 * Build page links.
	 *
	 * @access private
	 *
	 * @param int $startLinks
	 * @param int $endLinks
	 * @param string $url
	 *
	 * @return string
	 */
	private function pageLinks(int $startLinks, int $endLinks, string $url): string
	{
		$links = '';

		for ($page = $startLinks; $page <= $endLinks ; $page++) {
			$isCurrentPage = $this->currentPage === $page;
			$active = $isCurrentPage ? $this->listItemActiveClass : '';
			$pageUrl = "{$url}page={$page}";

			if ($isCurrentPage) {
				$links .= sprintf(
					'<li class="%s" aria-current="page"><span class="%s">%s</span></li>',
					trim($this->listItemClass . ' ' . $active),
					$this->listLinkClass,
					$page
				);

				continue;
			}

			$links .= sprintf(
				'<li class="%s"><a class="%s" href="%s" aria-label="%s">%s</a></li>',
				trim($this->listItemClass . ' ' . $active),
				$this->listLinkClass,
				$pageUrl,
				sprintf($this->listLinkAriaLabel, $page),
				$page
			);
		}

		return $links;
	}

	/**
	 * Build next and last links.
	 *
	 * @access private
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	private function nextAndLastLinks(string $url): string
	{
		$links = '';

		$nextPageUrl = '#';
		$lastPageUrl = '#';
		$disabled = $this->listItemDisabledClass;

		if ($this->currentPage < $this->totalPages) {
			$this->nextPage = $this->currentPage + 1;
			$this->nextPageUrl = "{$url}page={$this->nextPage}";
			$this->lastPageUrl = "{$url}page={$this->totalPages}";

			$nextPageUrl = $this->nextPageUrl;
			$lastPageUrl = $this->lastPageUrl;
			$disabled = '';
		}

		$format = '<li class="%s"><a class="%s" href="%s" aria-label="%s">%s</a></li>';

		$links .= sprintf(
			$format,
			trim($this->listItemClass . ' ' . $disabled),
			$this->listLinkClass,
			$nextPageUrl,
			$this->listLinkAriaLabelNext,
			$this->listLinkLabelNext
		);

		$links .= sprintf(
			$format,
			trim($this->listItemClass . ' ' . $disabled),
			$this->listLinkClass,
			$lastPageUrl,
			$this->listLinkAriaLabelLast,
			$this->listLinkLabelLast
		);

		return $links;
	}

	/**
	 * Query string.
	 *
	 * @access private
	 *
	 * @return string
	 */
	private function queryString(): string
	{
		if (!isset($_SERVER['QUERY_STRING'])) {
			return '';
		}

		$str = preg_replace("/(\?|&)?page=[0-9]+/i", '', $_SERVER['QUERY_STRING']);
		$str = str_replace(['?', '&'], ['', ''], $str);

		return strlen($str) > 0 ? $str . '&' : '';
	}
}
