<?php
declare(strict_types=1);

/**
 * Singleton PDO — MariaDB/MySQL.
 */
final class Database
{
    private static ?PDO $pdo = null;
    private static bool $failed = false;

    public static function pdo(): ?PDO
    {
        if (self::$failed) {
            return null;
        }
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $port = $_ENV['DB_PORT'] ?? '3306';
        $name = $_ENV['DB_NAME'] ?? 'chilenosproyeccion';
        $user = $_ENV['DB_USER'] ?? 'root';
        $pass = $_ENV['DB_PASS'] ?? '';

        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

        try {
            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (Throwable $e) {
            self::$failed = true;
            self::$pdo = null;
            error_log('[DB] ' . $e->getMessage());
            return null;
        }

        return self::$pdo;
    }

    public static function disponible(): bool
    {
        return self::pdo() instanceof PDO;
    }
}
