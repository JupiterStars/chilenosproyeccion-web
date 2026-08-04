<?php
declare(strict_types=1);

final class SuscriptorModel
{
    public static function registrar(string $email): bool
    {
        $email = strtolower(trim($email));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $pdo = Database::pdo();
        if (!$pdo) {
            return true; // demo: acepta sin persistir
        }
        try {
            $st = $pdo->prepare(
                'INSERT INTO suscriptores (email) VALUES (?)
                 ON DUPLICATE KEY UPDATE estado = \'activo\''
            );
            return $st->execute([$email]);
        } catch (Throwable $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
