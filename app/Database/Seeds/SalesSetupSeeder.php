<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SalesSetupSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // 1. Daftarkan role 'sales' beserta permission-nya di tabel roles
        $salesRole = $db->table('roles')->where('role_name', 'sales')->get()->getRowArray();
        
        $permissions = [
            'dashboard',        // Akses dashboard umum
            'sales_referrals',  // Mengklaim/menghubungkan supplier
            'sales_suppliers',  // Melihat daftar supplier yang dikelola
            'sales_products',   // Membantu input/edit produk supplier
            'sales'             // Akses modul/rute sales agen di dashboard
        ];
        
        $roleData = [
            'role_name'   => 'sales',
            'permissions' => json_encode($permissions)
        ];

        if (!$salesRole) {
            $db->table('roles')->insert($roleData);
            echo "Role 'sales' berhasil ditambahkan.\n";
        } else {
            $db->table('roles')->where('id', $salesRole['id'])->update($roleData);
            echo "Role 'sales' berhasil diperbarui.\n";
        }

        // 2. Buat akun sales uji coba di tabel user_admin jika belum ada
        $salesUser = $db->table('user_admin')->where('email', 'sales@pasangin.co.id')->get()->getRowArray();

        if (!$salesUser) {
            $db->table('user_admin')->insert([
                'full_name'    => 'Sales Representative Pasangin',
                'email'        => 'sales@pasangin.co.id',
                'password'     => password_hash('password123', PASSWORD_DEFAULT),
                'role'         => 'sales',
                'phone_number' => '081234567899',
                'photo'        => 'default.png',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s')
            ]);
            echo "Akun Sales uji coba (sales@pasangin.co.id / password123) berhasil dibuat.\n";
        } else {
            echo "Akun Sales uji coba sudah ada di database.\n";
        }
    }
}
