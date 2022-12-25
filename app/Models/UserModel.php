<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email', 'password', 'nama', 'nik', 'no_telp', 'user_level', 'date_created'];
}
