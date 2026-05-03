<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\SuratService;
use RuntimeException;

class Surat extends BaseController
{
    protected SuratService $svc;

    public function __construct()
    {
        $this->svc = new SuratService();
    }

    /**
     * Export PDF Kontrak Konstruksi.
     */
    public function exportPdf($id)
    {
        try {
            $result = $this->svc->generateConstructionPdf((int)$id);
            $this->svc->streamPdf($result['html'], $result['fileName']);
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Export PDF Kontrak Renovasi.
     */
    public function renovationExportPdf($id)
    {
        try {
            $result = $this->svc->generateRenovationPdf((int)$id);
            $this->svc->streamPdf($result['html'], $result['fileName']);
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
