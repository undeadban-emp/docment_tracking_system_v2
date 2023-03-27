<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "code";
    public $connection = 'doc_tracking';
    public $table = 'offices';
    protected $fillable = ['code', 'description', 'head', 'position', 'location', 'shortname'];

    public function services()
    {
        return $this->hasMany(Service::class, 'office', 'code');
    }
}
