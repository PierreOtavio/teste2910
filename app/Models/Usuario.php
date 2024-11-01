<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Usuario extends Model
{
    use HasFactory;
    use HasRoles;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'cpf',
        'email',
        'password',
        'role',
    ];
    public function hasPermission($permission)
    {
        $permissions = [
            'admin' => ['create', 'edit', 'delete', 'view'],
            'viewer' => ['view'],
        ];

        return isset($permissions[$this->role]) && in_array($permission, $permissions[$this->role]);
    }
}