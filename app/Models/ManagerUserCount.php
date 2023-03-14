<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerUserCount extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'value'];
}
