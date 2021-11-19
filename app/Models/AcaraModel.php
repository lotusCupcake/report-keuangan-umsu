<?php

namespace App\Models;

use CodeIgniter\Model;

class AcaraModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'acara';
    protected $primaryKey = 'acaraId';
    protected $allowedFields = ['acaraFlayer', 'acaraNama', 'acaraHari', 'acaraJamMulai', 'acaraJamAkhir', 'acaraPenyiar', 'acaraStatus', 'acaraArsip'];
    protected $returnType = 'object';
}
