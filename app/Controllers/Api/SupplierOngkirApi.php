<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use \App\Models\SupplierOngkirModel;
use Exception;

class SupplierOngkirApi extends BaseController
{
    use ResponseTrait;
    protected $supplierOngkir;

    public function __construct()
    {
        $this->supplierOngkir = new SupplierOngkirModel();
    }

    private function getSupplierId()
    {
        try {
            $authHeader = $this->request->getHeaderLine('Authorization');
            if (empty($authHeader))
                return null;

            $token = str_replace('Bearer ', '', $authHeader);
            $tokenParts = explode('.', $token);
            if (count($tokenParts) != 3)
                return null;

            $payload = json_decode(base64_decode($tokenParts[1]), true);
            return $payload['uid'] ?? null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function create()
    {
        $id_suppliers = $this->getSupplierId();
        if (!$id_suppliers) {
            return $this->respond(
                [
                    'status' => 401,
                    'message' => 'Supplier tidak ditemukan.',
                ],
                401
            );
        }

        //validasi input
        $rules = [
            'ongkir' => 'required|numeric',
            'min_distance' => 'required|decimal',
            'max_distance' => 'required|decimal',
        ];

        $messages = [
            'ongkir' => [
                'required' => 'Ongkir wajib diisi.',
                'numeric' => 'Ongkir harus berupa angka.'
            ],
            'min_distance' => [
                'required' => 'Jarak minimum wajib diisi.',
                'decimal' => 'Jarak minimum harus berupa angka.'
            ],
            'max_distance' => [
                'required' => 'Jarak maksimum wajib diisi.',
                'decimal' => 'Jarak maksimum harus berupa angka.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = $this->request->getPost();
        if (empty($input)) {
            $input = $this->request->getJSON(true) ?? [];
        }

        $data = [
            'id_suppliers' => $id_suppliers,
            'ongkir' => $input['ongkir'],
            'min_distance' => $input['min_distance'],
            'max_distance' => $input['max_distance'],
        ];

        try {
            $this->supplierOngkir->insert($data);
            return $this->respondCreated([
                'status' => 201,
                'message' => 'Ongkir supplier berhasil dibuat',
                'data' => $data
            ]);
        } catch (Exception $e) {
            return $this->respond([
                'status' => 500,
                'message' => 'Gagal membuat ongkir supplier',
            ], 500);
        }
    }

    public function update($id = null)
    {

        //validasi input
        $rules = [
            'ongkir' => 'numeric',
            'min_distance' => 'decimal',
            'max_distance' => 'decimal',
        ];

        $messages = [
            'ongkir' => [
                'required' => 'Ongkir wajib diisi.',
                'numeric' => 'Ongkir harus berupa angka.'
            ],
            'min_distance' => [
                'required' => 'Jarak minimum wajib diisi.',
                'decimal' => 'Jarak minimum harus berupa angka.'
            ],
            'max_distance' => [
                'required' => 'Jarak maksimum wajib diisi.',
                'decimal' => 'Jarak maksimum harus berupa angka.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = $this->request->getPost();
        if (empty($input)) {
            $input = $this->request->getJSON(true) ?? [];
        }

        $data = [
            'ongkir' => $input['ongkir'],
            'min_distance' => $input['min_distance'],
            'max_distance' => $input['max_distance'],
        ];

        try {
            $this->supplierOngkir->update($id, $data);
            return $this->respondCreated([
                'status' => 201,
                'message' => 'Ongkir supplier berhasil diupdate',
                'data' => $data
            ]);
        } catch (Exception $e) {
            return $this->respond([
                'status' => 500,
                'message' => 'Gagal mengupdate ongkir supplier',
            ], 500);
        }
    }

    public function delete($id = null)
    {
        $id_suppliers = $this->getSupplierId();
        if (!$id_suppliers) {
            return $this->respond(
                [
                    'status' => 401,
                    'message' => 'Supplier tidak ditemukan.',
                ],
                401
            );
        }

        $idOngkir = $this->supplierOngkir->where(['id' => $id, 'id_suppliers' => $id_suppliers])->first();
        if (!$idOngkir) {
            return $this->respond([
                'status' => false,
                'message' => 'Ongkir supplier tidak ditemukan.',
            ], 404);
        }

        $this->supplierOngkir->delete($idOngkir);
        return $this->respondDeleted(['status' => true, 'message' => 'Ongkir supplier berhasil dihapus.']);
    }

    public function getOngkirByIdSupplier()
    {
        $id_suppliers = $this->getSupplierId();
        if (!$id_suppliers) {
            return $this->respond(
                [
                    'status' => 401,
                    'message' => 'Supplier tidak ditemukan.',
                ],
                401
            );
        }

        $ongkir = $this->supplierOngkir->where(['id_suppliers' => $id_suppliers])->first();
        if (!$ongkir) {
            return $this->respond([
                'status' => false,
                'message' => 'Ongkir supplier tidak ditemukan.',
            ], 404);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Ongkir supplier ditemukan.',
            'data' => $ongkir
        ]);
    }

    public function getAllOngkir()
    {
        $ongkir = $this->supplierOngkir->findAll();
        if (!$ongkir) {
            return $this->respond([
                'status' => false,
                'message' => 'Ongkir supplier tidak ditemukan.',
            ], 404);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Ongkir supplier ditemukan.',
            'data' => $ongkir
        ]);
    }
}