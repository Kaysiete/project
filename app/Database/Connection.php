<?php
namespace app\Database;

class Connection
{
	private static string $host = "";
	private static int $port = 3306;
	private static string $database = "";
	private static string $user = "";
	private static string $password = "";


	/**
	 * Set database credentials
	 */
	public static function loadCredentials(): void
	{
		self::$host = $_ENV['DB_IP'] ?? "localhost";
		self::$database = $_ENV['DB_SCHEME'] ?? "WebSB";
		self::$user = $_ENV['DB_USER'] ?? "WebSB";
		self::$password = $_ENV['DB_PASS'] ?? "root";
		self::$port = $_ENV['DB_PORT'] ?? 3306;
	}

	/**
	 * Get WebSB MySQL host
	 * @return string
	 */
	public static function getHost(): string
	{
		return self::$host;
	}

	/**
	 * Get WebSB MySQL port
	 * @return int
	 */
	public static function getPort(): int
	{
		return self::$port;
	}

	/**
	 * Get WebSB MySQL database
	 * @return string
	 */
	public static function getDatabase(): string
	{
		return self::$database;
	}

	/**
	 * Get WebSB MySQL password
	 * @return string
	 */
	public static function getPassword(): string
	{
		return self::$password;
	}

	/**
	 * Get WebSB MySQL user
	 * @return string
	 */
	public static function getUser(): string
	{
		return self::$user;
	}

	/**
	 * Get WebSB MySQL DNS string
	 * @return string
	 */
	public static function getDNS() :string
	{
		return "mysql:host=" . self::$host . ":" . self::getPort() . ";dbname=" . self::getDatabase();
	}
}