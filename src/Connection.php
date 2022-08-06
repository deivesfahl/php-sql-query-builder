<?php

declare(strict_types=1);

namespace DB;

use PDO;
use PDOException;

/**
 * @author    Deives Fahl <dfahl.cps@gmail.com>
 * @copyright (c) 2022, Deives Fahl
 */
final class Connection
{
	private static ?PDO $pdo = null;

	/**
	 * Open database connection.
	 *
	 * @access public
	 *
	 * @throws PDOException
	 *
	 * @return PDO
	 */
	public static function open(string $host, int $port, string $database, string $username, string $password): PDO
	{
		if (!static::$pdo) {
			try {
				static::$pdo = new PDO("mysql:host={$host};port={$port};dbname={$database}", $username, $password, [
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				]);
			} catch (PDOException $exception) {
				throw $exception;
			}
		}

		return static::$pdo;
	}

	/**
	 * Close database connection.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public static function close(): void
	{
		static::$pdo = null;
	}
}
