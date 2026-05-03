<?php

namespace Config\Validations;

trait OrderRules
{
    /**
     * Aturan validasi untuk pembaruan status pesanan
     */
    public array $orderUpdateStatus = [
        'status' => 'required|in_list[PENDING,UNPAID,PAID,SETTLEMENT,SHIPPED,COMPLETED,CANCELLED]',
    ];

    public array $orderUpdateStatus_errors = [
        'status' => [
            'required' => 'Status pesanan wajib dipilih.',
            'in_list'  => 'Status pesanan tidak valid.',
        ],
    ];
}
