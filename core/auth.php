<?php

// core/auth.php

class Auth
{
    private const SESSION_KEY = 'auth_user_id';

    /**
     * Log the user in (store their ID in the session).
     */
    public static function login(int $userId): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION[self::SESSION_KEY] = $userId;
    }

    

    /**
     * Log the user out (remove their ID from the session).
     */
    public static function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        unset($_SESSION[self::SESSION_KEY]);
    }

    /**
     * Get the currently authenticated user's ID, or null if guest.
     */
    public static function userId(): ?int
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION[self::SESSION_KEY])) {
            return null;
        }

        return (int) $_SESSION[self::SESSION_KEY];
    }

    /*
      Check if there is a logged-in user.
     */
    public static function check(): bool
    {
        return self::userId() !== null;
    }

    /**
     * Get the full user record (or null) using your User model.
     * Optional, but convenient.
     */
    public static function user(): ?array
    {
        $userId = self::userId();
        if ($userId === null) {
            return null;
        }

        // Adjust the path if needed based on your folder structure.
        require_once __DIR__ . '/../app/models/User.php';

        $userModel = new User();
        return $userModel->findById($userId);
    }

}
