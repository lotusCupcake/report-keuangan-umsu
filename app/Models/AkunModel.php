<?php

namespace App\Models;

use CodeIgniter\Model;

class AkunModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email', 'username', 'password_hash', 'reset_hash', 'reset_at', 'reset_expires', 'activate_hash', 'status', 'status_message', 'active', 'force_pass_reset', 'created_at', 'created_update', 'deleted_at'];
    protected $returnType = 'object';

    public function getSpecificUser($where)
    {
        $builder = $this->table('users');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $builder->join('auth_groups', 'auth_groups.id  = auth_groups_users.group_id');
        $builder->where($where);
        $query = $builder->get();
        return $query;
    }
}
