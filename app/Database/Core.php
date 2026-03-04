<?php
namespace app\Database;

use PDO;
use PDOException;

class Core
{
	private static PDO $PDO_instance;

	/**
	 * Construct class
	 */
	public function __construct()
	{
		try {
			self::createPdoInstance();
		} catch (PDOException $PDOException) {
			throw new $PDOException;
		}
	}

	/**
	 * Get PDO instance
	 * @return PDO
	 */
	private static function getPDOInstance(): PDO
	{
		if (!isset(self::$PDO_instance) or !self::$PDO_instance) {
			self::createPdoInstance();
		}
		return self::$PDO_instance;
	}

	/**
	 * Create PDO instance
	 * @return void
	 */
	private static function createPdoInstance(): void
	{
		Connection::loadCredentials();
		$options = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_CASE => PDO::CASE_NATURAL,
			PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING,
			PDO::ATTR_EMULATE_PREPARES => false
		];
		self::$PDO_instance = new PDO(
			Connection::getDNS(),
			Connection::getUser(),
			Connection::getPassword(),
			$options
		);
	}

	/**
	 * Execute query
	 * @param string $sql_string
	 * @param array $values
	 * @param bool $return_insert_id
	 * @return string|false
	 */
	public static function exec(string $sql_string, array $values = [], bool $return_insert_id = false): string|false
	{
		try {
			$PDOStatement = self::getPDOInstance()->prepare($sql_string);
			$execute_return = $PDOStatement->execute($values);
			if ($return_insert_id) {
				return self::getPDOInstance()->lastInsertId();
			}
			return $execute_return;
		} catch (PDOException $PDOException) {
			throw new $PDOException;
		}
	}

	/**
	 * Query data
	 * @param string $sql_string
	 * @param array $values
	 * @param bool $single_record
	 * @return array
	 */
	public static function query(string $sql_string, array $values = [], bool $single_record = false): array
	{
		try {
			$PDOStatement = self::getPDOInstance()->prepare($sql_string);
			$PDOStatement->execute($values);
			$records = $PDOStatement->fetchAll();
			if ($single_record) {
				return $records[0] ?? [];
			}
			return $records;
		} catch (PDOException $PDOException) {
			throw new $PDOException;
		}
	}

	/**
	 * Set MySQL lc_time_names value
	 * @param string $name
	 * @return bool
	 */
	public static function setLcTimeName(string $name = 'nl_NL'): bool
	{
		$sql = "SET lc_time_names = '{$name}';";
		return boolval(Core::exec($sql));
	}

	/**
	 * Begin transaction
	 * Can use multiple execTransaction() before ending transaction
	 * @return bool
	 */
	public static function beginTransaction(): bool
	{
		try {
			if (self::getPDOInstance()->beginTransaction()) {
				return true;
			}
			return false;
		} catch (PDOException $PDOException) {
			throw new $PDOException;
		}
	}

	/**
	 * Execute query in started transaction
	 * @param string $sql_string
	 * @param array $values
	 * @param bool $return_insert_id
	 * @return bool|string
	 */
	public static function execTransaction(string $sql_string, array $values = [], bool $return_insert_id = false): string|false
	{
		try {
			$PDOStatement = self::getPDOInstance()->prepare($sql_string);
			$execute_return = $PDOStatement->execute($values);
			if ($return_insert_id) {
				return self::getPDOInstance()->lastInsertId();
			}
			return $execute_return;
		} catch (PDOException $PDOException) {
			throw new $PDOException;
		}
	}

	/**
	 * End transaction
	 * @return bool
	 */
	public static function endTransaction(): bool
	{
		try {
			if (self::getPDOInstance()->commit()) {
				return true;
			}
			self::getPDOInstance()->rollBack();
			return false;
		} catch (PDOException $PDOException) {
			self::getPDOInstance()->rollBack();
			throw new $PDOException;
		}
	}

	/**
	 * Roll back last transaction
	 * @return bool
	 */
	public static function rollbackTransaction(): bool
	{
		try {
			if (self::getPDOInstance()->rollBack()) {
				return true;
			}
			return false;
		} catch (PDOException $PDOException) {
			throw new $PDOException;
		}
	}

	/**
	 * Check if row exists
	 * @param string $table
	 * @param string $id_col
	 * @param $id
	 * @param bool $null
	 * @return mixed
	 */
	public static function rowExists(string $table, string $id_col, $id = null, bool $null = true): mixed
	{
		$false = $null ? null : false;
		if (empty($table) or empty($id_col) or empty($id)) {
			return $false;
		}
		return !empty(Core::query("select {$id_col} from {$table} where {$id_col} = ? limit 1;", [$id])) ? $id : $false;
	}
}
