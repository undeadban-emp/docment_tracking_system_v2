<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    public $connection = 'doc_tracking';
    public $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'username',
        'email',
        'password',
        'phone_number',
        'position',
        'status',
        'profile_picture',
        'office',
        'role',
        'profile_picture'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $appends = [
        'fullname'
    ];

    protected function fullname(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>  Str::upper($this->lastname) . ', ' . Str::upper($this->firstname) . ' ' . Str::upper($this->middlename) . ' ' . Str::upper($this->suffix)
        );
    }
    //receiver
    // /forwarder
    public function documents()
    {
        return $this->belongsToMany(Service::class, 'user_service', 'user_id', 'service_id')->withPivot(['tracking_number', 'user_id', 'service_index', 'remarks', 'status', 'forward_to', 'forwarded_by', 'returned_by', 'received_by', 'reasons', 'request_description', 'manager_id'])->withTimestamps()->using(UserService::class);
    }


    public function userOffice()
    {
        return $this->belongsTo(Office::class, 'office');
    }

    public function userPosition()
    {
        return $this->belongsTo(Position::class, 'position');
    }

    public function returned_documents()
    {
        return $this->belongsToMany(Service::class, 'user_service', 'returned_to', 'service_id')->withPivot(['tracking_number', 'user_id', 'service_index', 'remarks', 'status', 'forward_to', 'forwarded_by', 'returned_by', 'returned_to', 'received_by', 'reasons', 'request_description'])->withTimestamps()->using(UserService::class);
    }

    public function manager_user()
    {
        return $this->belongsTo(ManagerUser::class, 'id', 'user_id');
    }
}