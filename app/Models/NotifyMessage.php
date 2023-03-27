<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifyMessage extends Model
{
    use HasFactory;
    public $connection = 'doc_tracking';
    public $table = 'notify_messages';
    protected $fillable = ['phone_number', 'message', 'status'];
}
