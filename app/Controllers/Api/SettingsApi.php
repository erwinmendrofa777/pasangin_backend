<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Modules\Admin\Models\SystemSettingModel;

class SettingsApi extends BaseController
{
    use ResponseTrait;

    /**
     * Mengambil konfigurasi aktif pajak dan biaya aplikasi untuk kalkulasi di aplikasi seluler/klien.
     * 
     * GET /api/settings/tax-fee
     */
    public function getTaxFeeSettings()
    {
        $settingsModel = new SystemSettingModel();
        
        $taxRate = (float)$settingsModel->getVal('tax_rate', 0.00);
        $appFeeType = $settingsModel->getVal('app_fee_type', 'flat');
        $appFeeValue = (float)$settingsModel->getVal('app_fee_value', 0.00);

        return $this->respond([
            'status' => true,
            'message' => 'Berhasil mengambil pengaturan pajak dan biaya aplikasi.',
            'data' => [
                'tax_rate' => $taxRate,
                'app_fee_type' => $appFeeType,
                'app_fee_value' => $appFeeValue
            ]
        ]);
    }
}
