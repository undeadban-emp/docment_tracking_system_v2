<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;
    public $connection = 'doc_tracking';
    public $fillable = ['description', 'where_to_secure', 'service_id', 'is_required'];
    public $table = 'requirements';

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    protected $cast = [
        'is_required'       =>      'boolean'
    ];
}
