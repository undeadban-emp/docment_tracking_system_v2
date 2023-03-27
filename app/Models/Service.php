<?php

namespace App\Models;

use App\Models\UserService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;
    public $connection = 'doc_tracking';
    public $table = 'services';
    public $with = ['serviceOffice', 'requirements'];
    protected $fillable = [
        'name',
        'description',
        'office',
        'service_process_id',
    ];

    public function serviceOffice()
    {
        return $this->belongsTo(Office::class, 'office', 'code');
    }

    public function requirements()
    {
        return $this->hasMany(Requirement::class);
    }

    public function process()
    {
        return $this->hasMany(ServiceProcess::class, 'code', 'service_process_id')->orderBy('index', 'ASC');
    }

    public function user_document()
    {
        return $this->belongsToMany(User::class, 'user_service', 'service_id', 'user_id')->withPivot(['tracking_number', 'service_id', 'user_id', 'service_index', 'forwarded_by', 'forward_to', 'returned_by', 'returned_to', 'received_by', 'remarks', 'status', 'stage', 'reasons', 'request_description'])->withTimestamps()->using(UserService::class);
    }
}
