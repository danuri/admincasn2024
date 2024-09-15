<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tm_dokumen';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['dokumen','keterangan','status','created_by'];

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

    protected $db;

    public function __construct()
    {
      parent::__construct();

      $this->db = \Config\Database::connect('default', false);
    }

    public function unggahan($id)
    {
      $query = $this->db->query("SELECT
                                	lokasi_formasi.kode_bkn,
                                	lokasi_formasi.nama,
                                	tr_dokumen.attachment,
                                	tr_dokumen.id AS idattachment,
                                	tr_dokumen.created_at
                                FROM
                                	lokasi_formasi
                                	LEFT JOIN
                                	tr_dokumen
                                	ON
                                		lokasi_formasi.kode_bkn = tr_dokumen.kode_lokasi AND tr_dokumen.id_dokumen = '$id'");
      $result = $query->getResult();

      return $result;
    }
}
