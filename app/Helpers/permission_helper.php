<?php

/**
 * Permission Helper
 * -----------------------------------------------------------------------
 * Fungsi global untuk memeriksa hak akses modul dari session admin.
 * Gunakan di view maupun controller untuk menampilkan/menyembunyikan
 * elemen UI berdasarkan permission role yang sedang login.
 *
 * Cara pakai:
 *   <?php if (can('users')): ?>
 *       <a href="...">Tambah User</a>
 *   <?php endif; ?>
 */

if (! function_exists('can')) {
    /**
     * Cek apakah admin yang sedang login memiliki akses ke modul tertentu.
     *
     * Super Admin (memiliki 'super_admin_override' dalam permissions)
     * selalu mendapatkan akses ke semua modul.
     *
     * @param  string $module Nama modul / permission key (contoh: 'users', 'roles', 'wallet')
     * @return bool
     */
    function can(string $module): bool
    {
        $permissions = session()->get('permissions') ?? [];

        // Super admin bypass: akses semua modul
        if (in_array('super_admin_override', $permissions)) {
            return true;
        }

        return in_array($module, $permissions);
    }
}

if (! function_exists('canAny')) {
    /**
     * Cek apakah admin memiliki akses ke SALAH SATU dari beberapa modul.
     * Berguna untuk menampilkan section header di sidebar.
     *
     * @param  string[] $modules Daftar module key
     * @return bool
     */
    function canAny(array $modules): bool
    {
        foreach ($modules as $module) {
            if (can($module)) {
                return true;
            }
        }
        return false;
    }
}
