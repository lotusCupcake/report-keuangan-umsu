<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranBniModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'acd_student';
    protected $primaryKey = 'studentId';
    protected $returnType = 'object';
}
