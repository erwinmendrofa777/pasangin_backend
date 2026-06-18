<?php

namespace App\Modules\Tukang\Controllers\Admin;

use App\Controllers\BaseController;
use App\Modules\Tukang\Models\TukangSkillModel;

class TukangSkill extends BaseController
{
    protected TukangSkillModel $model;

    public function __construct()
    {
        $this->model = new TukangSkillModel();
        helper(['form', 'url']);
    }

    // -------------------------------------------------------------------------
    // 1. LIST
    // -------------------------------------------------------------------------
    public function index()
    {
        if (!can('tukang')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Anda tidak memiliki akses.');
        }

        return view('App\Modules\Tukang\Views\skill_index', [
            'title'  => 'Kelola Tukang Skill',
            'skills' => $this->model->orderBy('skill_name', 'ASC')->findAll(),
        ]);
    }

    // -------------------------------------------------------------------------
    // 2. STORE (CREATE)
    // -------------------------------------------------------------------------
    public function store()
    {
        if (!can('tukang')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $skillName = trim($this->request->getPost('skill_name'));

        if (!$this->validateData(['skill_name' => $skillName], $this->model->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Cek duplikat
        if ($this->model->where('skill_name', $skillName)->first()) {
            return redirect()->back()->withInput()->with('error', "Skill '{$skillName}' sudah ada.");
        }

        $this->model->insert(['skill_name' => $skillName]);
        log_admin_activity('create', 'TukangSkill', 'Menambah skill: ' . $skillName);

        return redirect()->to(base_url('admin/tukang-skill'))->with('success', 'Skill berhasil ditambahkan!');
    }

    // -------------------------------------------------------------------------
    // 3. UPDATE
    // -------------------------------------------------------------------------
    public function update()
    {
        if (!can('tukang')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $id        = (int) $this->request->getPost('id');
        $skillName = trim($this->request->getPost('skill_name'));

        if (!$this->validateData(['skill_name' => $skillName], $this->model->getValidationRules())) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        // Cek duplikat (exclude self)
        $existing = $this->model->where('skill_name', $skillName)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->with('error', "Skill '{$skillName}' sudah ada.");
        }

        $this->model->update($id, ['skill_name' => $skillName]);
        log_admin_activity('update', 'TukangSkill', 'Update skill ID ' . $id . ' → ' . $skillName);

        return redirect()->to(base_url('admin/tukang-skill'))->with('success', 'Skill berhasil diperbarui!');
    }

    // -------------------------------------------------------------------------
    // 4. DELETE
    // -------------------------------------------------------------------------
    public function delete($id)
    {
        if (!can('tukang')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $skill = $this->model->find((int) $id);
        if (!$skill) {
            return redirect()->back()->with('error', 'Skill tidak ditemukan.');
        }

        $this->model->delete((int) $id);
        log_admin_activity('delete', 'TukangSkill', 'Hapus skill: ' . $skill['skill_name']);

        return redirect()->to(base_url('admin/tukang-skill'))->with('success', 'Skill berhasil dihapus.');
    }
}
