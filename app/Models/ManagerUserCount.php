<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerUserCount extends Model
{
    use HasFactory;
    public $connection = 'doc_tracking';
    public $table = 'manager_user_counts';
    protected $fillable = ['name', 'value'];
}
