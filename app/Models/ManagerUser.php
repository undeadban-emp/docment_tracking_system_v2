<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerUser extends Model
{
    use HasFactory;
    public $connection = 'doc_tracking';
    public $table = 'manager_users';
    protected $fillable = ['manager_id', 'user_id'];
    public function users()
    {
        return $this->hasMany(User::class, 'user_id', 'id');
    }
    public function services_process()
    {
        return $this->belongsTo(ServiceProcess::class, 'manager_id', 'manager_id');
    }
    public function user_service()
    {
        return $this->belongsTo(UserService::class, 'manager_id', 'manager_id');
    }
}
