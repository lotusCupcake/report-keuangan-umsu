<?php

namespace App\Models;

use CodeIgniter\Model;

class PenyiarModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'penyiar';
    protected $primaryKey = 'penyiarId';
    protected $allowedFields = ['penyiarNama', 'penyiarStatus'];
    protected $returnType = 'object';
}
