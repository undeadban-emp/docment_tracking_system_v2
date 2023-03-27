<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;
    public $connection = 'doc_tracking';
    public $table = 'uploads';
    protected $fillable = ['transaction_code', 'file'];
}
