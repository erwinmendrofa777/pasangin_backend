<?php

namespace App\Modules\Admin\Models;

use CodeIgniter\Model;

class SystemSettingModel extends Model
{
    protected $table            = 'system_settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['setting_key', 'setting_value', 'setting_group', 'description'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Get a setting value by key.
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getVal(string $key, $default = null)
    {
        $row = $this->where('setting_key', $key)->first();
        return $row ? $row['setting_value'] : $default;
    }

    /**
     * Set/update a setting value by key.
     * 
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function setVal(string $key, string $value): bool
    {
        $row = $this->where('setting_key', $key)->first();
        if ($row) {
            return $this->update($row['id'], ['setting_value' => $value]);
        } else {
            return $this->insert([
                'setting_key'   => $key,
                'setting_value' => $value,
                'setting_group' => 'order',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]) !== false;
        }
    }
}
