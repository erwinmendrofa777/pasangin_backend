<?php

namespace Config\Validations;

trait PromoRules
{
    /**
     * Aturan validasi untuk pembaruan status promo oleh admin
     */
    public array $promoUpdateStatus = [
        'status' => 'required|in_list[active,inactive]',
    ];

    public array $promoUpdateStatus_errors = [
        'status' => [
            'required' => 'Status promo wajib dipilih.',
            'in_list'  => 'Status promo tidak valid (harus active atau inactive).',
        ],
    ];
}
