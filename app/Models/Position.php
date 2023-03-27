<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'code';
    public $connection = 'doc_tracking';
    public $table = 'positions';
    protected $fillable = ['code', 'position_name', 'position_short_name'];
}
