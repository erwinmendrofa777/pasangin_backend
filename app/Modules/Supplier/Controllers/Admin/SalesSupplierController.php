<?php

namespace App\Modules\Supplier\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Supplier\Models\SupplierModel;
use App\Modules\Supplier\Models\SupplierReferralModel;
use App\Modules\Products\Models\ProductModel;
use App\Modules\Products\Models\AppCategoryModel;
use App\Modules\Supplier\Models\CategoryModel;
use App\Modules\Satuan\Models\SatuanModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Exception;

class SalesSupplierController extends BaseController
{
    protected SupplierModel $supplierModel;
    protected SupplierReferralModel $referralModel;
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
        $this->referralModel = new SupplierReferralModel();
        $this->productModel = new ProductModel();
    }

    /**
     * Check if the logged-in user is Sales and is authorized to manage the supplier
     */
    private function checkSalesAuth($supplierId = null)
    {
        if (session()->get('role') !== 'sales') {
            throw new PageNotFoundException('Akses ditolak. Halaman ini khusus untuk Sales.');
        }

        if ($supplierId !== null) {
            $supplier = $this->supplierModel->where([
                'id'       => $supplierId,
                'sales_id' => session()->get('user_id')
            ])->first();

            if (!$supplier) {
                throw new PageNotFoundException('Supplier tidak ditemukan atau Anda tidak memiliki akses ke toko ini.');
            }
            return $supplier;
        }
        return null;
    }

    // -------------------------------------------------------------------------
    // 1. TAMPILAN KLAIM SUPPLIER (SCAN / INPUT KODE)
    // -------------------------------------------------------------------------
    public function claimView()
    {
        $this->checkSalesAuth();
        return view('App\Modules\Supplier\Views\supplier/sales/claim', [
            'title' => 'Klaim Toko Supplier'
        ]);
    }

    // -------------------------------------------------------------------------
    // 2. PROSES KLAIM SUPPLIER
    // -------------------------------------------------------------------------
    public function claimProcess()
    {
        $this->checkSalesAuth();

        $code = $this->request->getPost('code');
        if (empty($code)) {
            return redirect()->back()->withInput()->with('error', 'Kode referal wajib diisi.');
        }

        try {
            $refData = $this->referralModel->where('code', $code)->first();

            if (!$refData) {
                return redirect()->back()->withInput()->with('error', 'Kode referal tidak terdaftar di sistem.');
            }

            if ($refData['is_used'] == 1) {
                return redirect()->back()->withInput()->with('error', 'Kode referal sudah digunakan sebelumnya.');
            }

            if (strtotime($refData['expires_at']) < time()) {
                return redirect()->back()->withInput()->with('error', 'Kode referal telah kedaluwarsa.');
            }

            // Link sales ke supplier & update status kode referral
            $db = \Config\Database::connect();
            $db->transStart();

            $this->supplierModel->update($refData['supplier_id'], [
                'sales_id' => session()->get('user_id')
            ]);

            $this->referralModel->update($refData['id'], [
                'is_used' => 1
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan basis data saat mengklaim.');
            }

            $supplier = $this->supplierModel->find($refData['supplier_id']);
            return redirect()->to('/admin/sales/suppliers')->with('success', 'Berhasil mengklaim toko supplier: ' . $supplier['name']);

        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Sistem error: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 3. DAFTAR SUPPLIER SAYA
    // -------------------------------------------------------------------------
    public function mySuppliers()
    {
        $this->checkSalesAuth();
        $suppliers = $this->supplierModel->where('sales_id', session()->get('user_id'))->findAll();

        $productsBySupplier = [];
        $totalProducts = 0;
        $activeProducts = 0;
        $pendingProducts = 0;

        if (!empty($suppliers)) {
            $supplierIds = array_column($suppliers, 'id');
            $allProducts = $this->productModel->select('products.*, supplier_categories.name as category_name')
                ->join('supplier_categories', 'supplier_categories.id = products.supplier_category_id', 'left')
                ->whereIn('products.supplier_id', $supplierIds)
                ->orderBy('products.created_at', 'DESC')
                ->findAll();

            foreach ($allProducts as $p) {
                $productsBySupplier[$p['supplier_id']][] = $p;
                $totalProducts++;
                if ($p['status'] === 'aktif') {
                    $activeProducts++;
                }
                if ($p['approval_status'] === 'pending') {
                    $pendingProducts++;
                }
            }
        }

        return view('App\Modules\Supplier\Views\supplier/sales/my_suppliers', [
            'title'              => 'Supplier Saya',
            'suppliers'          => $suppliers,
            'productsBySupplier' => $productsBySupplier,
            'totalProducts'      => $totalProducts,
            'activeProducts'     => $activeProducts,
            'pendingProducts'    => $pendingProducts
        ]);
    }

    // -------------------------------------------------------------------------
    // 4. DAFTAR PRODUK SUPPLIER TERTENTU
    // -------------------------------------------------------------------------
    public function supplierProducts($supplierId)
    {
        return redirect()->to("/admin/sales/suppliers?supplier_id={$supplierId}");
    }

    public function supplierProductsAjax($supplierId)
    {
        try {
            $supplier = $this->checkSalesAuth($supplierId);
            $products = $this->productModel->getProductsBySupplier($supplierId);

            return view('App\Modules\Supplier\Views\supplier/sales/partials/_products_table', [
                'supplier' => $supplier,
                'products' => $products
            ]);
        } catch (Exception $e) {
            return '<div class="alert alert-danger p-3 m-3">Gagal memuat produk: ' . esc($e->getMessage()) . '</div>';
        }
    }

    // -------------------------------------------------------------------------
    // 5. TAMBAH PRODUK BARU
    // -------------------------------------------------------------------------
    public function createProduct($supplierId)
    {
        $supplier = $this->checkSalesAuth($supplierId);
        helper('form');

        // Ambil kategori supplier milik supplier ini untuk dropdown
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->where('supplier_id', $supplierId)->orderBy('name', 'ASC')->findAll();

        // Ambil master satuan dari database
        $satuanModel = new SatuanModel();
        $satuans = $satuanModel->orderBy('nama_satuan', 'ASC')->findAll();

        return view('App\Modules\Supplier\Views\supplier/sales/create_product', [
            'title'      => 'Tambah Produk Baru',
            'supplier'   => $supplier,
            'categories' => $categories,
            'satuans'    => $satuans
        ]);
    }

    // -------------------------------------------------------------------------
    // 6. SIMPAN PRODUK
    // -------------------------------------------------------------------------
    public function storeProduct($supplierId)
    {
        $this->checkSalesAuth($supplierId);

        $input = $this->request->getPost();
        $file = $this->request->getFile('photo');

        if (!$this->validate('productSave')) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        $photoName = 'default.png';
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $photoName = $file->getRandomName();
            $file->move('uploads/products/', $photoName);
        }

        try {
            $this->productModel->insert([
                'supplier_id'          => $supplierId,
                'supplier_category_id' => !empty($input['supplier_category_id']) ? $input['supplier_category_id'] : null,
                'app_category_id'      => null, // Diisi oleh admin saat verifikasi
                'name'                 => $input['name'],
                'description'          => $input['description'] ?? null,
                'price'                => $input['price'],
                'unit'                 => $input['unit'] ?? 'pcs',
                'stock'                => $input['stock'] ?? 0,
                'min_order'            => $input['min_order'] ?? 1,
                'quantity'             => $input['quantity'] ?? 0,
                'status'               => 'tidak aktif',
                'approval_status'      => 'pending',
                'photo'                => $photoName,
            ]);

            return redirect()->to("/admin/sales/suppliers?supplier_id={$supplierId}")->with('success', 'Produk berhasil ditambahkan dan sedang menunggu persetujuan Admin.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 7. FORM EDIT PRODUK
    // -------------------------------------------------------------------------
    public function editProduct($supplierId, $productId)
    {
        $supplier = $this->checkSalesAuth($supplierId);
        helper('form');

        $product = $this->productModel->where(['id' => $productId, 'supplier_id' => $supplierId])->first();
        if (!$product) {
            throw new PageNotFoundException('Produk tidak ditemukan.');
        }

        $categoryModel = new CategoryModel();
        $categories = $categoryModel->where('supplier_id', $supplierId)->orderBy('name', 'ASC')->findAll();

        // Ambil master satuan dari database
        $satuanModel = new SatuanModel();
        $satuans = $satuanModel->orderBy('nama_satuan', 'ASC')->findAll();

        return view('App\Modules\Supplier\Views\supplier/sales/edit_product', [
            'title'      => 'Edit Produk',
            'supplier'   => $supplier,
            'product'    => $product,
            'categories' => $categories,
            'satuans'    => $satuans
        ]);
    }

    // -------------------------------------------------------------------------
    // 8. UPDATE PRODUK
    // -------------------------------------------------------------------------
    public function updateProduct($supplierId, $productId)
    {
        $this->checkSalesAuth($supplierId);

        $product = $this->productModel->where(['id' => $productId, 'supplier_id' => $supplierId])->first();
        if (!$product) {
            throw new PageNotFoundException('Produk tidak ditemukan.');
        }

        $input = $this->request->getPost();
        $file = $this->request->getFile('photo');

        if (!$this->validate('productUpdate')) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errors);
        }

        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!empty($product['photo']) && file_exists('uploads/products/' . $product['photo'])) {
                unlink('uploads/products/' . $product['photo']);
            }
            $newName = $file->getRandomName();
            $file->move('uploads/products/', $newName);
            $input['photo'] = $newName;
        }

        try {
            $this->productModel->update($productId, [
                'supplier_category_id' => !empty($input['supplier_category_id']) ? $input['supplier_category_id'] : null,
                'name'                 => $input['name'],
                'description'          => $input['description'] ?? null,
                'price'                => $input['price'],
                'unit'                 => $input['unit'] ?? 'pcs',
                'stock'                => $input['stock'] ?? 0,
                'min_order'            => $input['min_order'] ?? 1,
                'quantity'             => $input['quantity'] ?? 0,
                'photo'                => $input['photo'] ?? $product['photo'],
            ]);

            return redirect()->to("/admin/sales/suppliers?supplier_id={$supplierId}")->with('success', 'Produk berhasil diperbarui.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 9. HAPUS PRODUK
    // -------------------------------------------------------------------------
    public function deleteProduct($supplierId, $productId)
    {
        $this->checkSalesAuth($supplierId);

        $product = $this->productModel->where(['id' => $productId, 'supplier_id' => $supplierId])->first();
        if (!$product) {
            throw new PageNotFoundException('Produk tidak ditemukan.');
        }

        try {
            if (!empty($product['photo']) && file_exists('uploads/products/' . $product['photo']) && $product['photo'] !== 'default.png') {
                unlink('uploads/products/' . $product['photo']);
            }

            $this->productModel->delete($productId);
            return redirect()->to("/admin/sales/suppliers?supplier_id={$supplierId}")->with('success', 'Produk berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->to("/admin/sales/suppliers?supplier_id={$supplierId}")->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 10. LEPAS SUPPLIER
    // -------------------------------------------------------------------------
    public function releaseSupplier($supplierId)
    {
        $supplier = $this->checkSalesAuth($supplierId);

        try {
            $this->supplierModel->update($supplierId, [
                'sales_id' => null
            ]);
            return redirect()->to('/admin/sales/suppliers')->with('success', 'Berhasil melepas toko supplier: ' . $supplier['name']);
        } catch (Exception $e) {
            return redirect()->to('/admin/sales/suppliers')->with('error', 'Gagal melepas supplier: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // 11. SIMPAN KATEGORI BARU (AJAX)
    // -------------------------------------------------------------------------
    public function storeCategory($supplierId)
    {
        try {
            $this->checkSalesAuth($supplierId);

            $name = $this->request->getPost('name');
            if (empty($name)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Nama kategori tidak boleh kosong.'
                ]);
            }

            $categoryModel = new CategoryModel();
            
            $existing = $categoryModel->where([
                'supplier_id' => $supplierId,
                'name'        => $name
            ])->first();

            if ($existing) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Kategori ini sudah terdaftar untuk supplier ini.'
                ]);
            }

            $id = $categoryModel->insert([
                'supplier_id' => $supplierId,
                'name'        => $name
            ]);

            return $this->response->setJSON([
                'success'    => true,
                'category'   => [
                    'id'   => $id,
                    'name' => $name
                ],
                'csrf_token' => csrf_token(),
                'csrf_hash'  => csrf_hash()
            ]);

        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Sistem error: ' . $e->getMessage()
            ]);
        }
    }

    public function storeSatuan()
    {
        try {
            $this->checkSalesAuth();

            $name = $this->request->getPost('name');
            $name = trim($name);

            if (empty($name)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Nama satuan tidak boleh kosong.'
                ]);
            }

            $satuanModel = new SatuanModel();
            
            $existing = $satuanModel->where('nama_satuan', $name)->first();

            if ($existing) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Satuan ini sudah terdaftar di sistem.'
                ]);
            }

            $id = $satuanModel->insert([
                'nama_satuan' => $name
            ]);

            return $this->response->setJSON([
                'success'    => true,
                'satuan'     => [
                    'id'   => $id,
                    'name' => $name
                ],
                'csrf_token' => csrf_token(),
                'csrf_hash'  => csrf_hash()
            ]);

        } catch (Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Sistem error: ' . $e->getMessage()
            ]);
        }
    }
}
