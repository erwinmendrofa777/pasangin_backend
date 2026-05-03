<?php

namespace App\Validation;

class UserRules
{
    /**
     * Contoh validasi kustom: Memastikan string mengandung setidaknya satu karakter spesial.
     */
    public function has_special_char(string $str, ?string &$error = null): bool
    {
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $str)) {
            $error = 'Field {field} harus mengandung setidaknya satu karakter spesial.';
            return false;
        }

        return true;
    }

    /**
     * Contoh validasi kustom: Cek apakah umur minimal terpenuhi.
     */
    public function min_age(string $str, string $age, array $data): bool
    {
        $birthDate = new \DateTime($str);
        $today = new \DateTime();
        $diff = $today->diff($birthDate);

        return $diff->y >= (int)$age;
    }
}
