<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Modules\Admin\Models\SystemSettingModel;

/**
 * @internal
 */
final class SystemSettingsTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate   = true;
    protected $namespace = 'App';

    public function testGetValAndSetVal(): void
    {
        $model = new SystemSettingModel();

        // 1. Uji mengambil nilai default yang telah di-seeding oleh migrasi
        $taxRate = $model->getVal('tax_rate');
        $this->assertNotNull($taxRate, 'Nilai default tax_rate tidak boleh null');
        $this->assertEquals('11', $taxRate, 'Nilai default tax_rate harus 11');

        // 2. Uji mengubah nilai setelan
        $success = $model->setVal('tax_rate', '12');
        $this->assertTrue($success, 'Gagal melakukan setVal');

        $newTaxRate = $model->getVal('tax_rate');
        $this->assertEquals('12', $newTaxRate, 'Nilai tax_rate tidak terupdate setelah diubah');

        // 3. Kembalikan nilai ke setelan semula
        $model->setVal('tax_rate', '11');
    }
}
