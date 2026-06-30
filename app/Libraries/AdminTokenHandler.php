<?php

namespace App\Libraries;



use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AdminTokenHandler
{
    /**
     * Get JWT Secret Key from Environment
     */
    private static function getKey(): string
    {
        return getenv('JWT_SECRET') ?: 'default_secret_key_change_me_in_env';
    }

    /**
     * Generate JWT for Admin
     * 
     * @param array $adminData Data of the logged-in admin
     * @return string Signed JWT token
     */
    public static function generate(array $adminData): string
    {
        $key = self::getKey();
        $currentTime = time();
        
        $payload = [
            'iss'         => base_url(),
            'aud'         => base_url(),
            'iat'         => $currentTime,
            'nbf'         => $currentTime,
            'exp'         => $currentTime + (2 * 3600), // Expire in 2 hours
            'user_id'     => $adminData['user_id'] ?? $adminData['id'],
            'full_name'   => $adminData['full_name'] ?? 'Admin',
            'email'       => $adminData['email'],
            'role'        => $adminData['role'],
            'photo'       => $adminData['photo'] ?? null,
            'permissions' => $adminData['permissions'] ?? []
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    /**
     * Verify and Decode JWT Token
     * 
     * @param string $token JWT Token
     * @return array|null Decoded payload or null if invalid/expired
     */
    public static function verify(string $token): ?array
    {
        try {
            $key = self::getKey();
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            log_message('error', '[AdminTokenHandler] verification failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Set HttpOnly Cookie for JWT
     * 
     * @param string $token JWT Token
     */
    public static function setCookie(string $token): void
    {
        helper('cookie');
        
        // Expiration in 2 hours (7200 seconds)
        // Using CodeIgniter 4 set_cookie helper
        set_cookie([
            'name'     => 'admin_jwt',
            'value'    => $token,
            'expire'   => 7200,
            'path'     => '/',
            'secure'   => false, // Set to true in HTTPS production environments
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }

    /**
     * Delete/Expire HttpOnly Cookie
     */
    public static function deleteCookie(): void
    {
        helper('cookie');
        delete_cookie('admin_jwt');
    }
}
