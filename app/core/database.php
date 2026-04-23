<?php

/**
 * Database Connection Class
 * Secured with OOP (Singleton Pattern) and PDO.
 * - Uses prepared statements to prevent SQL injection
 * - Singleton pattern ensures a single connection instance
 * - Strict error mode with exceptions
 * - UTF-8 charset enforced
 * - Emulated prepares disabled for real server-side prepared statements
 */
class Database
{
    // ── Connection credentials ──────────────────────────────
    private const HOST     = 'localhost';
    private const DB_NAME  = 'lris_db';
    private const USERNAME = 'root';
    private const PASSWORD = '';
    private const CHARSET  = 'utf8mb4';

    // ── Singleton instance ──────────────────────────────────
    private static ?Database $instance = null;

    // ── PDO connection handle ───────────────────────────────
    private ?PDO $pdo = null;

    /**
     * Private constructor — prevents direct instantiation.
     * Establishes the PDO connection with security-hardened options.
     */
    private function __construct()
    {
        $dsn = 'mysql:host=' . self::HOST
             . ';dbname='    . self::DB_NAME
             . ';charset='   . self::CHARSET;

        $options = [
            // Throw exceptions on errors (never silent failures)
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

            // Return results as associative arrays by default
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            // Use real prepared statements (not emulated) — stronger SQL-injection defense
            PDO::ATTR_EMULATE_PREPARES   => false,

            // Disable multi-query execution to prevent stacked-query injection
            PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
        ];

        try {
            $this->pdo = new PDO($dsn, self::USERNAME, self::PASSWORD, $options);
        } catch (PDOException $e) {
            // Log the real error privately; show a generic message to the user
            error_log('Database connection failed: ' . $e->getMessage());
            die('Database connection failed. Please try again later.');
        }
    }

    /**
     * Prevent cloning of the singleton instance.
     */
    private function __clone() {}

    /**
     * Prevent unserialization of the singleton instance.
     */
    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize a singleton.');
    }

    // ── Public API ──────────────────────────────────────────

    /**
     * Get the single Database instance (creates it on first call).
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get the raw PDO connection (for advanced use).
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * Execute a parameterized query and return the PDOStatement.
     *
     * Usage:
     *   $stmt = Database::getInstance()->query(
     *       "SELECT * FROM users WHERE email = :email",
     *       ['email' => $email]
     *   );
     *   $user = $stmt->fetch();
     *
     * @param  string $sql    SQL with named placeholders
     * @param  array  $params Associative array of placeholder => value
     * @return PDOStatement
     */
    public function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Fetch a single row from a parameterized query.
     *
     * @param  string     $sql
     * @param  array      $params
     * @return array|false
     */
    public function fetchOne(string $sql, array $params = []): array|false
    {
        return $this->query($sql, $params)->fetch();
    }

    /**
     * Fetch all rows from a parameterized query.
     *
     * @param  string $sql
     * @param  array  $params
     * @return array
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Get the last inserted auto-increment ID.
     */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Get the number of rows affected by the last INSERT/UPDATE/DELETE.
     *
     * @param  string $sql
     * @param  array  $params
     * @return int
     */
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Begin a database transaction.
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit the current transaction.
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * Roll back the current transaction.
     */
    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }
}
