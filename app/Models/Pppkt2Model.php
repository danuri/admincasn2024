<?php

namespace App\Models;

use CodeIgniter\Model;

class Pppkt2Model extends Model
{
    protected $table            = 'pppk_t2';
    protected $primaryKey       = 'nopeserta';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = [];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    function getSatker() {
        $query = $this->db->query("SELECT kode_satker_asal,satker_asal, COUNT(nopeserta) AS jumlah FROM pppk_t2 WHERE surat_keterangan IS NOT NULL GROUP BY kode_satker_asal,satker_asal")->getResult();
        return $query;
    }
}
