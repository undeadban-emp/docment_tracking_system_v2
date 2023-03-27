<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProcess extends Model
{
    use HasFactory;
    public $connection = 'doc_tracking';
    public $table = 'service_processes';

    protected $fillable = ['code', 'responsible', 'action', 'location' ,'description', 'index', 'fees_to_paid', 'responsible_user', 'manager_id'];

    public function office()
    {
        return $this->hasOne(Office::class, 'code', 'location');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'responsible_user');
    }

    public function manager()
    {
        return $this->hasOne(User::class, 'id', 'manager_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'code', 'service_process_id');
    }

    public function sub_process()
    {
        return $this->hasMany(SubServiceProcess::class, 'service_process_id', 'id');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($process) {
            $process->index = ServiceProcess::where('code', $process->code)->max('index') + 1;
        });
    }
    public function managerUser()
    {
        return $this->hasMany(ManagerUser::class, 'manager_id', 'manager_id');
    }
}
