<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'config';
    protected $primaryKey = 'configId';
    protected $allowedFields = ['configNama', 'configValue'];
    protected $returnType = 'object';
}
