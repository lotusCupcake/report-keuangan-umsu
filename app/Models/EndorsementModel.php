<?php

namespace App\Models;

use CodeIgniter\Model;

class EndorsementModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'endorsment';
    protected $primaryKey = 'endorsmentId';
    protected $allowedFields = ['endorsmentFlayer', 'endorsmentNama', 'endorsmentTanggalAwal', 'endorsmentTanggalAkhir', 'endorsmentDeskripsi'];
    protected $returnType = 'object';
}
