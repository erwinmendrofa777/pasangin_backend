<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RabModel;
use CodeIgniter\API\ResponseTrait;

class RabController extends BaseController
{
    use ResponseTrait;

    protected $rabModel;
    protected $db;

    public function __construct()
    {
        $this->rabModel = new RabModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * 1. SIMPAN ATAU UPDATE BARIS PEKERJAAN
     * Mendukung Roman Number, Group Name, dan Proteksi Lock
     */
    public function save_rab_row()
    {
        $id = $this->request->getPost('id');
        $constructionId = $this->request->getPost('construction_id');
        
        // Ambil data grouping kawan
        $roman = $this->request->getPost('roman_number') ?: 'I';
        $group = $this->request->getPost('group_name') ?: 'PEKERJAAN';
        $section = $this->request->getPost('section_group');
        
        $taskName = $this->request->getPost('task_name');
        $volume = (float) ($this->request->getPost('volume') ?? 0);
        $unit = $this->request->getPost('unit');
        $price = (float) ($this->request->getPost('price') ?? 0);

        // Hitung total price otomatis kawan
        $totalPrice = $volume * $price;

        // Cek jika ini update, apakah sudah dikunci?
        if (!empty($id) && $id != "0") {
            $existing = $this->db->table('construction_rabs')->where('id', $id)->get()->getRowArray();
            if ($existing && $existing['is_locked'] == 1) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Maaf kawan, baris ini sudah dikunci!'
                ]);
            }
        }

        $data = [
            'construction_id'    => $constructionId,
            'roman_number'       => $roman,
            'group_name'         => $group,
            'sub_group_name'     => $section,
            'section_group'      => $section,
            'section_name'       => $section,
            'activity_name'      => $taskName,
            'volume'             => $volume,
            'unit'               => $unit,
            'current_unit_price' => $price,
            'total_price'        => $totalPrice,
            'updated_at'         => date('Y-m-d H:i:s')
        ];

        try {
            if (empty($id) || $id == "0") {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->db->table('construction_rabs')->insert($data);
                $finalId = $this->db->insertID();
                $message = "Berhasil tambah baris baru kawan!";
            } else {
                $this->db->table('construction_rabs')->where('id', $id)->update($data);
                $finalId = $id;
                $message = "Baris RAB diperbarui kawan!";
            }

            return $this->response->setJSON([
                'status'  => true,
                'id'      => $finalId,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * 2. HAPUS BARIS PEKERJAAN (Proteksi Lock)
     */
    public function delete_rab_row($id)
    {
        try {
            $existing = $this->db->table('construction_rabs')->where('id', $id)->get()->getRowArray();
            if ($existing && $existing['is_locked'] == 1) {
                return $this->response->setJSON(['status' => false, 'message' => 'Gagal! Baris ini terkunci kawan.']);
            }

            if ($this->db->table('construction_rabs')->where('id', $id)->delete()) {
                // Hapus juga relasi materialnya kawan
                $this->db->table('construction_rab_materials')->where('rab_id', $id)->delete();

                return $this->response->setJSON([
                    'status'  => true,
                    'message' => 'Baris berhasil dihapus kawan'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * 3. LOCK & UNLOCK FUNGSI (Untuk Admin)
     */
    public function lock_rab($constructionId)
    {
        $this->db->table('construction_rabs')
                 ->where('construction_id', $constructionId)
                 ->update(['is_locked' => 1]);
        
        return redirect()->back()->with('success', 'RAB Berhasil Dikunci kawan!');
    }

    public function unlock_rab($constructionId)
    {
        $this->db->table('construction_rabs')
                 ->where('construction_id', $constructionId)
                 ->update(['is_locked' => 0]);
        
        return redirect()->back()->with('success', 'Kunci RAB dibuka kawan!');
    }

    /**
     * 4. AMBIL PILIHAN MATERIAL
     */
    public function get_rab_materials($rabId)
    {
        $materials = $this->db->table('construction_rab_materials') 
            ->select('construction_rab_materials.id, products.name as material_name, products.price')
            ->join('products', 'products.id = construction_rab_materials.product_id')
            ->where('rab_id', $rabId) 
            ->get()->getResultArray();

        return $this->response->setJSON($materials);
    }

    /**
     * 5. TAMBAH PILIHAN MATERIAL
     */
    public function add_rab_material()
    {
        $rabId = $this->request->getPost('rab_id');
        $productId = $this->request->getPost('product_id');

        // Cek lock kawan
        $existing = $this->db->table('construction_rabs')->where('id', $rabId)->get()->getRowArray();
        if ($existing && $existing['is_locked'] == 1) {
            return $this->response->setJSON(['status' => false, 'message' => 'Tidak bisa tambah, RAB terkunci!']);
        }

        try {
            $this->db->table('construction_rab_materials')->insert([
                'rab_id'     => $rabId,
                'product_id' => $productId,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON(['status' => true, 'message' => 'Material ditambahkan kawan.']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * 6. HAPUS PILIHAN MATERIAL
     */
    public function delete_rab_material($id)
    {
        try {
            $this->db->table('construction_rab_materials')->where('id', $id)->delete();
            return $this->response->setJSON(['status' => true, 'message' => 'Material dihapus kawan.']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}