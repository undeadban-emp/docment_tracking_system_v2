<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserService extends Pivot
{
    public $table = 'user_service';
    public $connection = 'doc_tracking';
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value == 'disapproved') ? 'returned' : $value,
        );
    }

    public function receiver()
    {
        return $this->hasOne(ServiceProcess::class, 'index', 'service_index');
    }

    public function received_by_user()
    {
        return $this->hasOne(User::class, 'id', 'received_by');
    }

    public function returnee()
    {
        return $this->hasOne(User::class, 'id', 'returned_by');
    }

    public function return_to()
    {
        return $this->hasOne(User::class, 'id', 'returned_to');
    }

    public function information()
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }

    public function forwarded_by_user()
    {
        return $this->hasOne(User::class, 'id', 'forwarded_by');
    }

    public function forwarded_to_user()
    {
        return $this->hasOne(User::class, 'id', 'forward_to');
    }

    public function avail_by()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function manager_users()
    {
        return $this->hasMany(ManagerUser::class, 'manager_id', 'manager_id');
    }

}
