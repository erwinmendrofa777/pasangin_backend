<?php

namespace App\Modules\Admin\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Admin\Models\SystemSettingModel;
use RuntimeException;

class AppSettingsController extends BaseController
{
    protected SystemSettingModel $settingsModel;

    public function __construct()
    {
        $this->settingsModel = new SystemSettingModel();
    }

    /**
     * Menampilkan form pengaturan pajak & biaya aplikasi.
     */
    public function index()
    {
        if (!can('settings_view')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses untuk melihat pengaturan aplikasi.');
        }

        $settings = [
            'tax_rate'      => (float)$this->settingsModel->getVal('tax_rate', 11.00),
            'app_fee_type'  => $this->settingsModel->getVal('app_fee_type', 'flat'),
            'app_fee_value' => (float)$this->settingsModel->getVal('app_fee_value', 2000.00),
        ];

        return view('App\Modules\Admin\Views\settings/index', [
            'title'    => 'Pengaturan Aplikasi',
            'settings' => $settings
        ]);
    }

    /**
     * Memproses update pengaturan dari form admin.
     */
    public function update()
    {
        if (!can('settings_edit')) {
            return redirect()->to('/admin/settings')->with('error', 'Anda tidak memiliki akses untuk mengubah pengaturan aplikasi.');
        }

        $rules = [
            'tax_rate'      => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
            'app_fee_type'  => 'required|in_list[flat,percentage]',
            'app_fee_value' => 'required|numeric|greater_than_equal_to[0]',
        ];

        $messages = [
            'tax_rate' => [
                'required'                 => 'Tarif pajak wajib diisi.',
                'numeric'                  => 'Tarif pajak harus berupa angka.',
                'greater_than_equal_to'    => 'Tarif pajak minimal 0%.',
                'less_than_equal_to'       => 'Tarif pajak maksimal 100%.',
            ],
            'app_fee_type' => [
                'required' => 'Tipe biaya aplikasi wajib dipilih.',
                'in_list'  => 'Tipe biaya aplikasi tidak valid.',
            ],
            'app_fee_value' => [
                'required'              => 'Nilai biaya aplikasi wajib diisi.',
                'numeric'               => 'Nilai biaya aplikasi harus berupa angka.',
                'greater_than_equal_to' => 'Nilai biaya aplikasi minimal 0.',
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        try {
            $taxRate = $this->request->getPost('tax_rate');
            $appFeeType = $this->request->getPost('app_fee_type');
            $appFeeValue = $this->request->getPost('app_fee_value');

            $this->settingsModel->setVal('tax_rate', $taxRate);
            $this->settingsModel->setVal('app_fee_type', $appFeeType);
            $this->settingsModel->setVal('app_fee_value', $appFeeValue);

            log_admin_activity('update', 'Settings', 'Mengubah pengaturan Pajak dan Biaya Aplikasi (Pajak: ' . $taxRate . '%, Tipe: ' . $appFeeType . ', Nilai: ' . $appFeeValue . ')');

            return redirect()->to('/admin/settings')->with('success', 'Pengaturan aplikasi berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan pengaturan: ' . $e->getMessage());
        }
    }
}
