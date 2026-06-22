<?php

namespace App\Modules\Tukang\Config\Validations;

trait TukangRules
{
    /**
     * Aturan validasi untuk pendaftaran mitra tukang baru
     */
    public array $tukangSave = [
        'name'             => 'required|min_length[3]|max_length[255]',
        'email'            => 'required|valid_email|is_unique[tukang.email]',
        'phone'            => 'required|numeric|min_length[9]|max_length[15]',
        'skills'           => 'required',
        'nik'              => 'required|exact_length[16]|numeric',
        'gender'           => 'required|in_list[Laki-laki,Perempuan]',
        'dob'              => 'required|valid_date',
        'ktp_address'      => 'required',
        'domicile_address' => 'required',
        'profile_photo'    => 'uploaded[profile_photo]|is_image[profile_photo]|max_size[profile_photo,2048]',
        'ktp_photo'        => 'uploaded[ktp_photo]|is_image[ktp_photo]|max_size[ktp_photo,2048]',
        'selfie_photo'     => 'uploaded[selfie_photo]|is_image[selfie_photo]|max_size[selfie_photo,2048]',
    ];

    public array $tukangSave_errors = [
        'name' => [
            'required'   => 'Nama lengkap wajib diisi.',
            'min_length' => 'Nama lengkap minimal 3 karakter.',
            'max_length' => 'Nama lengkap maksimal 255 karakter.',
        ],
        'email' => [
            'required'    => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
            'is_unique'   => 'Email ini sudah terdaftar sebagai mitra tukang.',
        ],
        'phone' => [
            'required'   => 'Nomor telepon wajib diisi.',
            'numeric'    => 'Nomor telepon hanya boleh angka.',
            'min_length' => 'Nomor telepon minimal 9 digit.',
            'max_length' => 'Nomor telepon maksimal 15 digit.',
        ],
        'skills' => [
            'required' => 'Minimal satu keahlian wajib dipilih.',
        ],
        'nik' => [
            'required'     => 'NIK wajib diisi.',
            'exact_length' => 'NIK harus tepat 16 digit angka.',
            'numeric'      => 'NIK hanya boleh berisi angka.',
        ],
        'gender' => [
            'required' => 'Jenis kelamin wajib dipilih.',
            'in_list'  => 'Jenis kelamin harus Laki-laki atau Perempuan.',
        ],
        'dob' => [
            'required'   => 'Tanggal lahir wajib diisi.',
            'valid_date' => 'Format tanggal lahir tidak valid.',
        ],
        'ktp_address' => [
            'required' => 'Alamat sesuai KTP wajib diisi.',
        ],
        'domicile_address' => [
            'required' => 'Alamat domisili saat ini wajib diisi.',
        ],
        'profile_photo' => [
            'uploaded' => 'Foto profil wajib diunggah.',
            'is_image' => 'File harus berupa gambar.',
            'max_size' => 'Ukuran foto profil maksimal 2MB.',
        ],
        'ktp_photo' => [
            'uploaded' => 'Foto KTP wajib diunggah.',
            'is_image' => 'File harus berupa gambar.',
            'max_size' => 'Ukuran foto KTP maksimal 2MB.',
        ],
        'selfie_photo' => [
            'uploaded' => 'Foto selfie dengan KTP wajib diunggah.',
            'is_image' => 'File harus berupa gambar.',
            'max_size' => 'Ukuran foto selfie maksimal 2MB.',
        ],
    ];

    /**
     * Aturan validasi untuk update status verifikasi
     */
    public array $tukangUpdateVerify = [
        'is_verify' => 'required|in_list[0,1]',
    ];

    public array $tukangUpdateVerify_errors = [
        'is_verify' => [
            'required' => 'Status verifikasi wajib dipilih.',
            'in_list'  => 'Status verifikasi tidak valid.',
        ],
    ];

    /**
     * Aturan validasi untuk update status mitra
     */
    public array $tukangUpdateStatus = [
        'status' => 'required|in_list[Aktif,Nonaktif,Berkas Diproses,Ditolak,Suspended,Proses Test,Proses Aktivasi,Siap Kerja]',
    ];

    public array $tukangUpdateStatus_errors = [
        'status' => [
            'required' => 'Status mitra wajib dipilih.',
            'in_list'  => 'Status mitra tidak valid.',
        ],
    ];
}
