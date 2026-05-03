<?php

namespace Config\Validations;

trait TukangRules
{
    /**
     * Aturan validasi untuk pendaftaran mitra tukang baru
     */
    public array $tukangSave = [
        'name'             => 'required|min_length[3]|max_length[255]',
        'email'            => 'required|valid_email|is_unique[tukang.email]',
        'phone'            => 'required|numeric|min_length[9]|max_length[15]',
        'specialization'   => 'required',
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
        'email' => [
            'is_unique' => 'Email ini sudah terdaftar sebagai mitra tukang.',
        ],
        'nik' => [
            'exact_length' => 'NIK harus tepat 16 digit angka.',
            'numeric'      => 'NIK hanya boleh berisi angka.',
        ],
        'profile_photo' => [
            'uploaded' => 'Foto profil wajib diunggah.',
        ],
        'ktp_photo' => [
            'uploaded' => 'Foto KTP wajib diunggah.',
        ],
        'selfie_photo' => [
            'uploaded' => 'Foto selfie dengan KTP wajib diunggah.',
        ],
    ];

    /**
     * Aturan validasi untuk update status verifikasi
     */
    public array $tukangUpdateVerify = [
        'is_verify' => 'required|in_list[0,1]',
    ];

    /**
     * Aturan validasi untuk update status mitra
     */
    public array $tukangUpdateStatus = [
        'status' => 'required|in_list[Aktif,Nonaktif,Berkas Diproses,Ditolak,Suspended]',
    ];
}
