<?php

namespace App\Modules\Orders\Config\Validations;

trait OrderRules
{
    /**
     * Aturan validasi untuk pembaruan status pesanan
     */
    public array $orderUpdateStatus = [
        'status' => 'required|in_list[PENDING,UNPAID,PAID,SETTLEMENT,PROCESSED,SHIPPED,COMPLETED,CANCELLED]',
    ];

    public array $orderUpdateStatus_errors = [
        'status' => [
            'required' => 'Status pesanan wajib dipilih.',
            'in_list'  => 'Status pesanan tidak valid.',
        ],
    ];

    /**
     * Aturan validasi untuk proses checkout (API)
     */
    public array $apiOrderCheckout = [
        'recipient_name'   => 'required|min_length[3]',
        'recipient_phone'  => 'required|numeric|min_length[10]',
        'shipping_address' => 'required|min_length[10]',
        'total_price'      => 'required|numeric',
    ];

    public array $apiOrderCheckout_errors = [
        'recipient_name' => [
            'required'   => 'Nama penerima wajib diisi.',
            'min_length' => 'Nama penerima minimal 3 karakter.',
        ],
        'recipient_phone' => [
            'required'   => 'Nomor telepon penerima wajib diisi.',
            'numeric'    => 'Nomor telepon harus berupa angka.',
            'min_length' => 'Nomor telepon minimal 10 digit.',
        ],
        'shipping_address' => [
            'required'   => 'Alamat pengiriman wajib diisi.',
            'min_length' => 'Alamat pengiriman terlalu pendek.',
        ],
        'total_price' => [
            'required' => 'Total harga wajib diisi.',
            'numeric'  => 'Total harga harus berupa angka.',
        ],
    ];
}
